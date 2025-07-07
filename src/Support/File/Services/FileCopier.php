<?php

declare(strict_types=1);

namespace Support\File\Services;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Support\File\Enums\FileTypeEnum;
use Support\File\Exceptions\UnableToCopyFileException;
use Support\File\Models\File;
use Support\File\Repositories\FileRepositoryInterface;
use Support\File\Repositories\Input\FileStoreInput;
use Support\File\Traits\EnsuresFolders;

class FileCopier
{
    use EnsuresFolders;

    public function __construct(
        private readonly FileRepositoryInterface $fileRepository,
    ) {
    }

    /**
     * Copies given files with given type and sub-folder
     *
     * @param  File[]  $files
     * @param  string[]  $folders
     * @return EloquentCollection<File>
     *
     * @throws \Exception
     */
    public function copyFiles(
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
                    $path = $storage->putFile($subFolder, new \Illuminate\Http\File($file->real_path));

                    throw_if($path === false, new UnableToCopyFileException($file, $type, $subFolder));

                    // save new file path in case the process fails
                    $paths[] = $path;

                    try {
                        $model = $this->fileRepository->store(new FileStoreInput(
                            model: $fileable,
                            type: $type,
                            path: $path,
                            extension: $file->extension,
                            name: $file->name,
                            mime: $file->mime,
                            size: $file->size,
                            data: $file->data,
                        ));
                    } catch (\Exception $e) {
                        throw new UnableToCopyFileException($file, $type, $subFolder, previous: $e);
                    }

                    $models->push($model);
                }

                return $models;
            }, attempts: 5);
        } catch (\Exception $e) {
            report($e);

            // delete created files
            $storage->delete($paths);

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
}
