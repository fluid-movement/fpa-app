<?php

namespace App\Core\Service;

use GuzzleHttp\Client;
use Spatie\Geocoder\Exceptions\CouldNotGeocode;
use Spatie\Geocoder\Geocoder;

class GeocodingService
{
    private Geocoder $geocoder;

    public function __construct()
    {
        $this->geocoder = new Geocoder(new Client);
        $this->geocoder->setApiKey(config('geocoder.key'));
    }

    public function getCoordinates(string $address): array
    {
        $data = $this->geocoder->getCoordinatesForAddress($address);

        return [$data['lat'] ?? 0, $data['lng'] ?? 0];
    }

    public function getData(string $address): array
    {
        return $this->geocoder->getCoordinatesForAddress($address);
    }

    public function getList(string $address): array
    {
        try {
            $data = $this->geocoder->getAllCoordinatesForAddress($address);
            if (isset($data[0]['accuracy']) && $data[0]['accuracy'] === 'result_not_found') {
                return [];
            }

            return $data;
        } catch (CouldNotGeocode $exception) {
            return [];
        }
    }
}
