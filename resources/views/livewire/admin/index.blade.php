<?php

use Livewire\Attributes\Computed;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new #[\Livewire\Attributes\Title('FPA Members')] class extends Component
{
    use WithPagination;

    #[\Livewire\Attributes\Url(except: '')]
    public string $search = '';

    #[\Livewire\Attributes\Url(except: '')]
    public string $filter = '';

    #[Computed]
    public function players(): \Illuminate\Pagination\LengthAwarePaginator
    {
        $filterQuery = match ($this->filter) {
            'active' => fn ($query) => $query->whereHas('activeYears', function ($q) {
                $q->where('year', now()->year)
                    ->orWhere(function ($q) {
                        $q->where('year', now()->year - 1)
                            ->whereMonth('created_at', '>=', 10);
                    });
            }),
            'inactive' => fn ($query) => $query->whereDoesntHave('activeYears', function ($q) {
                $q->where('year', now()->year)
                    ->orWhere(function ($q) {
                        $q->where('year', now()->year - 1)
                            ->whereMonth('created_at', '>=', 10);
                    });
            }),
            default => null,
        };

        $activeYearCount = function ($q) {
            $q->where(function ($q) {
                $q->where('year', now()->year)
                    ->orWhere(function ($q) {
                        $q->where('year', now()->year - 1)
                            ->whereMonth('created_at', '>=', 10);
                    });
            });
        };

        return \App\Models\Player::query()
            ->withCount(['activeYears as recent_active_years_count' => $activeYearCount])
            ->when($this->search, fn ($query) => $query->where(function ($q) {
                $q->where('name', 'ILIKE', "%{$this->search}%")
                    ->orWhere('surname', 'ILIKE', "%{$this->search}%");
            }))
            ->when($this->filter, $filterQuery)
            ->paginate(20);
    }
}; ?>

<div class="flex flex-col gap-8">
    <flux:button class="self-center w-1/2" icon="plus" href="{{ route('admin.members.create') }}">
        Add new member
    </flux:button>
    <flux:input wire:model.live="search" placeholder="Search members..."/>
    <flux:radio.group wire:model.live="filter" label="Status" variant="segmented">
        <flux:radio value="" label="All"/>
        <flux:radio value="active" label="Active"/>
        <flux:radio value="inactive" label="Inactive"/>
    </flux:radio.group>
    <flux:table :paginate="$this->players">
        <flux:table.columns>
            <flux:table.column>Name</flux:table.column>
            <flux:table.column>#</flux:table.column>
            <flux:table.column>Status</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @foreach ($this->players as $player)
                <flux:table.row :key="$player->id">
                    <flux:table.cell class="flex items-center gap-3">
                        {{ $player->name }} {{ $player->surname }}
                    </flux:table.cell>
                    <flux:table.cell class="whitespace-nowrap">#{{ $player->member_number }}</flux:table.cell>
                    <flux:table.cell>
                        <flux:badge size="sm" color="{{ $player->recent_active_years_count > 0 ? 'green' : 'amber' }}">
                            {{ $player->recent_active_years_count > 0 ? 'Active' : 'Inactive' }}
                        </flux:badge>
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:dropdown>
                            <flux:button variant="ghost"
                                         size="sm"
                                         icon="ellipsis-horizontal"
                                         inset="top bottom">
                            </flux:button>
                            <flux:menu>
                                <flux:text
                                    class="flex p-2 cursor-default">{{ $player->name }} {{ $player->surname }}</flux:text>
                                <flux:menu.separator/>
                                <flux:menu.item icon="cog-6-tooth" class="cursor-pointer"
                                                href="{{route('admin.members.edit', $player)}}">Manage
                                </flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>
</div>
