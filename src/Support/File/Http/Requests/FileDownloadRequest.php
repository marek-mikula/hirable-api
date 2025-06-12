<?php

declare(strict_types=1);

namespace Support\File\Http\Requests;

use App\Http\Requests\AuthRequest;
use Support\File\Policies\FilePolicy;

class FileDownloadRequest extends AuthRequest
{
    public function authorize(): bool
    {
        /** @see FilePolicy::download() */
        return $this->user()->can('download', $this->route('file'));
    }

    public function rules(): array
    {
        return [];
    }
}
