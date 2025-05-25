<?php

use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component
{
    public float $latitude = 0;

    public float $longitude = 0;

    public string $address = '';

    public string $selectedAddress = '';

    public array $options = [];

    public function updatedAddress()
    {
        if ($this->address && strlen($this->address) > 3) {
            $geocoder = new \App\Services\GeocodingService;
            $this->options = $geocoder->getList($this->address);
        }
    }

    public function updatedSelectedAddress()
    {
        if ($this->selectedAddress) {
            $geocoder = new \App\Services\GeocodingService;
            $data = $geocoder->getData($this->selectedAddress);
            [$this->latitude, $this->longitude] = [$data['lat'], $data['lng']];
            $this->dispatch('coordinatesChanged', $data);
        }
    }

    #[On('pin-dropped')]
    public function setCoordinates($lat, $lng)
    {
        $this->latitude = $lat;
        $this->longitude = $lng;
    }
};
?>

<div>
    <flux:select
        wire:model="selectedAddress"
        variant="listbox"
        searchable
        placeholder="Search for address..."
        class="mb-32"
    >
        <x-slot name="search">
            <flux:select.search
                wire:model.live.debounce.200ms="address"
                class="px-4"
                placeholder="Search Addresses..."
            />
        </x-slot>
        @if($options)
            @foreach($options as $option)
                <flux:select.option wire:key="{{ $option['place_id'] }}">
                    {{ $option['formatted_address'] }}
                </flux:select.option>
            @endforeach
        @endif
    </flux:select>

    <flux:text>{{ $selectedAddress }}</flux:text>
    <flux:text>{{ $latitude }}</flux:text>
    <flux:text>{{ $longitude }}</flux:text>

    <div id="map" wire:ignore style="height: 400px;"></div>
</div>

@script
<script>
    (g => {
        var h, a, k, p = "The Google Maps JavaScript API", c = "google", l = "importLibrary", q = "__ib__",
            m = document, b = window;
        b = b[c] || (b[c] = {});
        var d = b.maps || (b.maps = {}), r = new Set, e = new URLSearchParams,
            u = () => h || (h = new Promise(async (f, n) => {
                await (a = m.createElement("script"));
                e.set("libraries", [...r] + "");
                for (k in g) e.set(k.replace(/[A-Z]/g, t => "_" + t[0].toLowerCase()), g[k]);
                e.set("callback", c + ".maps." + q);
                a.src = `https://maps.${c}apis.com/maps/api/js?` + e;
                d[q] = f;
                a.onerror = () => h = n(Error(p + " could not load."));
                a.nonce = m.querySelector("script[nonce]")?.nonce || "";
                m.head.append(a)
            }));
        d[l] ? console.warn(p + " only loads once. Ignoring:", g) : d[l] = (f, ...n) => r.add(f) && u().then(() => d[l](f, ...n))
    })({
        key: "load me", // todo load key here
        v: "weekly",
    });

    let map;
    let currentMarker = null; // Store the current marker reference

    async function initMap() {
        const {Map} = await google.maps.importLibrary("maps");

        map = new Map(document.getElementById("map"), {
            center: {lat: {{$latitude}}, lng: {{$longitude}}},
            zoom: 2,
        });

        // Add click listener to allow users to place pins.
        map.addListener('click', function (e) {
            // Remove the existing marker if one exists.
            if (currentMarker) {
                currentMarker.setMap(null);
            }
            // Create a new marker at the clicked location and store it.
            currentMarker = new google.maps.Marker({
                position: e.latLng,
                map: map
            });

            console.log('User placed pin at: ', e.latLng.lat(), e.latLng.lng());
            // Emit the new coordinates to Livewire.
            $wire.dispatch('pin-dropped', {lat: e.latLng.lat(), lng: e.latLng.lng()});
        });
    }

    initMap();

    document.addEventListener('livewire:init', () => {
        Livewire.on('coordinatesChanged', event => {
            console.log('Re-centering map:', event);
            if (!map) {
                return;
            }
            const newPos = {lat: event[0].lat, lng: event[0].lng};
            map.setCenter(newPos);
            // Update the marker position if it exists, otherwise create a new one.
            console.log('current marker: ', currentMarker)
            if (currentMarker) {
                currentMarker.setPosition(newPos);
            } else {
                currentMarker = new google.maps.Marker({
                    position: newPos,
                    map: map
                });
            }
            // Optionally adjust zoom level based on accuracy.
            switch (event[0].accuracy) {
                case 'ROOFTOP':
                    map.setZoom(18);
                    break;
                case 'RANGE_INTERPOLATED':
                    map.setZoom(16);
                    break;
                case 'GEOMETRIC_CENTER':
                    map.setZoom(14);
                    break;
                case 'APPROXIMATE':
                    map.setZoom(12);
                    break;
            }
        });
    });
</script>
@endscript
