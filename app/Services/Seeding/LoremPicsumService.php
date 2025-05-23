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

    public function __construct(
        private readonly AssetManagerService $assetManagerService
    ) {}

    public function getPicture(): string
    {
        return self::process(AssetType::Picture);
    }

    private function process(AssetType $type): string
    {
        $fileName = Str::random(40).'.jpg';
        $size = $type === AssetType::Picture ? '1920/600' : '200';
        $url = self::URL.$size;
        if (! $image = self::getImage($url)) {
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
