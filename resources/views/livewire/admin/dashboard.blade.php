<?php

use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;

new #[Title('Dashboard')] class extends Component
{
    #[Computed]
    public function players(): \Illuminate\Database\Eloquent\Collection
    {
        return \App\Models\Player::with('activeYears')->get();
    }

    #[Computed]
    public function years(): array
    {
        $allMembershipTypes = collect(\App\Enums\MembershipType::cases())
            ->map(fn ($type) => $type->name)
            ->toArray();

        return $this->players
            ->flatMap(fn ($member) => $member->activeYears->map(fn ($year) => [
                'year' => $year->year,
                'type' => $year->membership_type->name,
            ]))
            ->groupBy('year')
            ->map(function ($entries, $year) use ($allMembershipTypes) {
                $totalMembers = $entries->count();

                // Count each membership type for the year
                $membershipCounts = $entries
                    ->groupBy('type')
                    ->map(fn ($group) => $group->count())
                    ->toArray();

                // Ensure all membership types are present with a count of 0 if not found
                $membershipCounts = array_merge(array_fill_keys($allMembershipTypes, 0), $membershipCounts);

                return array_merge([
                    'year' => $year,
                    'players' => $totalMembers,
                ], $membershipCounts);
            })
            ->sortKeys()
            ->values()
            ->toArray();
    }

    #[Computed]
    public function currentMembers(): array
    {
        $current = array_filter(
            $this->years,
            fn ($entry) => $entry['year'] === now()->year
        ) ?? [];

        return reset($current); // return first element
    }
}; ?>
<div class="flex flex-col gap-8">
    <div class="flex gap-4 w-full">
        <x-facts-card number="{{ $this->currentMembers['players'] ?? 0 }}" text="Active players"/>
        <x-facts-card number="{{ $this->players->count() }}" text="Total members"/>
    </div>
    <flux:separator />
    <flux:heading>Memberships this year</flux:heading>
    <div class="flex gap-4 w-full">
        @foreach(\App\Enums\MembershipType::cases() as $type)
            <x-facts-card variant="sm" number="{{ $this->currentMembers[$type->name] ?? 0 }}" text="{{$type->getDisplayName()}}"/>
        @endforeach
    </div>
    <flux:separator />
    <flux:heading>Memberships over the years</flux:heading>
    <flux:chart :value="$this->years" class="aspect-3/1">
        <flux:chart.svg>
            <flux:chart.line field="players" class="text-blue-400"/>
            @foreach(\App\Enums\MembershipType::cases() as $type)
                <flux:chart.line field="{{$type->name}}" class="text-blue-400"/>
            @endforeach
            <flux:chart.axis axis="x" field="year">
                <flux:chart.axis.line/>
                <flux:chart.axis.tick/>
            </flux:chart.axis>
            <flux:chart.axis axis="y">
                <flux:chart.axis.grid/>
                <flux:chart.axis.tick/>
            </flux:chart.axis>
            <flux:chart.cursor/>
        </flux:chart.svg>

        <flux:chart.tooltip>
            <flux:chart.tooltip.heading field="year" :format=" ['useGrouping' => false] "/>
            <flux:chart.tooltip.value field="players" label="Members"/>
            @foreach(\App\Enums\MembershipType::cases() as $type)
                <flux:chart.tooltip.value field="{{$type->name}}" label="{{$type->getDisplayName()}}"/>
            @endforeach
        </flux:chart.tooltip>
    </flux:chart>
</div>
