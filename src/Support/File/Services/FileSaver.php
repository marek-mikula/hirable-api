<?php

declare(strict_types=1);

namespace Support\File\Services;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Support\File\Data\FileData;
use Support\File\Enums\FileTypeEnum;
use Support\File\Exceptions\UnableToSaveFileException;
use Support\File\Models\File;
use Support\File\Repositories\FileRepositoryInterface;
use Support\File\Repositories\Input\FileStoreInput;
use Support\File\Repositories\ModelHasFilesRepositoryInterface;

class FileSaver
{
    public function __construct(
        private readonly FileRepositoryInterface $fileRepository,
        private readonly ModelHasFilesRepositoryInterface $modelHasFilesRepository,
    ) {
    }

    /**
     * Saves given files to the local storage with given type and sub-folder
     *
     * @param  FileData[]  $files
     * @param  string[]  $folders
     * @return EloquentCollection<File>
     *
     * @throws \Exception
     */
    public function saveFiles(
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
                    $path = $storage->putFile($subFolder, $file->file);

                    throw_if($path === false, new UnableToSaveFileException($file, $type, $subFolder));

                    // save new file path in case the process fails
                    $paths[] = $path;

                    try {
                        $model = $this->fileRepository->store(new FileStoreInput(
                            type: $type,
                            path: $path,
                            extension: $file->getExtension(),
                            name: $file->getName(),
                            mime: $file->getMime(),
                            size: $file->getSize(),
                            data: $file->data,
                        ));
                    } catch (\Exception $e) {
                        throw new UnableToSaveFileException($file, $type, $subFolder, previous: $e);
                    }

                    $models->push($model);
                }

                // make a relationship between fileable and files
                $this->modelHasFilesRepository->storeMany($fileable, $models);

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

    private function ensureFolders(Filesystem $storage, array $folders): array
    {
        if (empty($folders)) {
            return [];
        }

        $incrementFolders = [];

        for ($x = 0; $x < count($folders); $x++) {
            $incrementFolders[$x] = DIRECTORY_SEPARATOR.collect(array_slice($folders, 0, $x + 1))->implode(DIRECTORY_SEPARATOR);
        }

        $result = [];

        foreach ($incrementFolders as $folder) {
            // create directory only if it does not
            // already exist
            $result[$folder] = !$storage->exists($folder) && $storage->makeDirectory($folder);
        }

        return $result;
    }
}
