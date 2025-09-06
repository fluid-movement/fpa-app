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
        \Log::info('AssetManagerService: Attempting to store temporary file', [
            'asset_type' => $type->value,
            'path' => $type->getPath(),
            'disk' => $this->disk,
            'file_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
        ]);

        try {
            // Check if the disk exists and is configured
            $diskConfig = config("filesystems.disks.{$this->disk}");
            \Log::info('AssetManagerService: Disk configuration', [
                'disk' => $this->disk,
                'config_exists' => !is_null($diskConfig),
                'driver' => $diskConfig['driver'] ?? 'unknown'
            ]);

            $result = $file->store($type->getPath(), $this->disk);
            
            if ($result) {
                \Log::info('AssetManagerService: File stored successfully', [
                    'result_path' => $result,
                    'disk' => $this->disk
                ]);
            } else {
                \Log::error('AssetManagerService: File storage failed - store() returned false', [
                    'disk' => $this->disk,
                    'path' => $type->getPath()
                ]);
            }
            
            return $result;
        } catch (\Exception $e) {
            \Log::error('AssetManagerService: Exception during file storage', [
                'error' => $e->getMessage(),
                'disk' => $this->disk,
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
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
