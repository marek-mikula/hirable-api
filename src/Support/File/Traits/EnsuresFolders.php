<?php

declare(strict_types=1);

namespace Support\File\Traits;

use Illuminate\Contracts\Filesystem\Filesystem;

trait EnsuresFolders
{
    protected function ensureFolders(Filesystem $storage, array $folders): array
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
