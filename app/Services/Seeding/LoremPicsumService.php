<?php

namespace App\Services\Seeding;

use App\Enums\AssetType;
use App\Services\AssetManagerService;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class LoremPicsumService
{
    const string URL = 'https://picsum.photos/';

    const int WIDTH = 1920;
    const int HEIGHT = 600;

    public function __construct(
        private readonly AssetManagerService $assetManagerService
    ) {
    }

    public function getPicture(): string
    {
        return self::process(AssetType::Picture);
    }

    public function getWidth(): int
    {
        return self::WIDTH;
    }

    public function getHeight(): int
    {
        return self::HEIGHT;
    }

    private function process(AssetType $type): string
    {
        $fileName = Str::random(40) . '.jpg';
        $size = $type === AssetType::Picture ? self::WIDTH . '/' . self::HEIGHT : '200';
        $url = self::URL . $size;
        if (!$image = self::getImage($url)) {
            return '';
        }

        return $this->assetManagerService->store(
            $type,
            $fileName,
            $image
        ) ? $fileName : '';
    }

    private function getImage(string $url): string
    {
        try {
            $response = Http::get($url);
            if ($response->failed()) {
                throw new Exception("Failed to download image from: {$url}");
            }

            return $response->body();
        } catch (Exception) {
            // todo log this error
        }

        return '';
    }
}
