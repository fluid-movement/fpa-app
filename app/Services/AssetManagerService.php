<?php

namespace App\Services;

use App\Enums\AssetType;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

readonly class AssetManagerService
{
    public function __construct(
        private string $disk = 'r2' // todo make configurable via .env or config file
    )
    {
    }

    public function store(AssetType $type, string $fileName, string $contents): bool
    {
        return Storage::disk($this->disk)->put($this->getPath($type, $fileName), $contents);
    }

    public function storeTemporary(AssetType $type, TemporaryUploadedFile $file): string|false
    {
        return $file->store($type->getPath(), $this->disk);
    }

    public function delete(AssetType $type, string $fileName): bool
    {
        return Storage::disk($this->disk)->delete($this->getPath($type, $fileName));
    }

    public function deleteAll(AssetType $type): bool
    {
        return Storage::disk($this->disk)->deleteDirectory($type->getPath());
    }

    public function url(string $fileName): string
    {
        return Storage::disk($this->disk)->url($fileName);
    }

    private function getPath(AssetType $type, string $fileName): string
    {
        return $type->getPath() . '/' . $fileName;
    }
}
