<?php

declare(strict_types=1);

namespace Support\File\Services;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Support\File\Enums\FileTypeEnum;
use Support\File\Exceptions\UnableToMoveFileException;
use Support\File\Models\File;
use Support\File\Repositories\FileRepositoryInterface;
use Support\File\Repositories\Input\FileUpdateInput;
use Support\File\Traits\EnsuresFolders;
use Illuminate\Support\Facades\File as FileFacade;

class FileMover
{
    use EnsuresFolders;

    public function __construct(
        private readonly FileRepositoryInterface $fileRepository,
    ) {
    }

    /**
     * Moves given files with given type and sub-folder
     *
     * @param  File[]  $files
     * @param  string[]  $folders
     * @return EloquentCollection<File>
     *
     * @throws \Exception
     */
    public function moveFiles(
        Model $fileable,
        FileTypeEnum $type,
        array $files,
        array $folders = []
    ): EloquentCollection {
        $storage = Storage::disk($type->getDomain()->getDisk());

        // build full relative path to subdirectory
        // if any
        //
        // example:
        // ['1_1000', '345', 'documents'] -> '/1_1000/345/documents'
        // [] -> '/'
        $subFolder = DIRECTORY_SEPARATOR.collect($folders)->implode(DIRECTORY_SEPARATOR);

        // ensures all folder are correctly
        // created and returns info, which
        // folders were created
        //
        // [
        //  "/1_1000" => false,
        //  "/1_1000/345" => true,
        //  "/1_1000/345/documents" => true,
        // ]
        $folders = $this->ensureFolders($storage, $folders);

        $paths = [];

        try {
            return DB::transaction(function () use (
                $fileable,
                $type,
                $files,
                $subFolder,
                $storage,
                &$paths,
            ): EloquentCollection {
                $models = modelCollection(File::class);

                foreach ($files as $file) {
                    $newPath = $this->getNewPath($file, $subFolder);
                    $newRealPath = $storage->path($newPath);

                    $moved = FileFacade::move($file->real_path, $newRealPath);

                    throw_if($moved === false, new UnableToMoveFileException($file, $type, $subFolder));

                    // save old file path and new file path in case the process fails
                    $paths[] = [$file->real_path, $newRealPath];

                    try {
                        $model = $this->fileRepository->update($file, new FileUpdateInput(
                            model: $fileable,
                            type: $type,
                            path: $newPath,
                        ));
                    } catch (\Exception $e) {
                        throw new UnableToMoveFileException($file, $type, $subFolder, previous: $e);
                    }

                    $models->push($model);
                }

                return $models;
            }, attempts: 5);
        } catch (\Exception $e) {
            report($e);

            // move back moved files
            foreach ($paths as [$oldPath, $newPath]) {
                FileFacade::move($newPath, $oldPath);
            }

            // delete created folders if any
            //
            // flip the array, so we are going
            // from the deepest folders to the
            // shallowest
            //
            // if we hit a folder, which was not
            // created, that means we can break
            // the loop, because logically shallower
            // folders were not created either
            foreach (array_reverse($folders, preserve_keys: true) as $folder => $status) {
                if (!$status) {
                    break;
                }

                $storage->deleteDirectory($folder);
            }

            throw $e;
        }
    }

    private function getNewPath(File $file, string $subFolder): string
    {
        if (Str::endsWith($subFolder, '/')) {
            return $subFolder === '/' ? $file->filename : sprintf('%s%s', $subFolder, $file->filename);
        }

        return sprintf('%s%s%s', $subFolder, DIRECTORY_SEPARATOR, $file->filename);
    }
}
