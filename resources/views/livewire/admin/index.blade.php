<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;

new #[\Livewire\Attributes\Title('Admin | FPA Members')] class extends Component
{
    use WithPagination;

    public string $sortBy = 'member_number';

    public string $sortDirection = 'asc';

    public string $search = '';

    public function sort($column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    #[\Livewire\Attributes\Computed]
    public function players(): \Illuminate\Pagination\LengthAwarePaginator
    {
        return \App\Models\Player::query()
            ->when($this->sortBy, fn ($query) => $query->orderBy($this->sortBy, $this->sortDirection))
            ->when($this->search, fn ($query) => $query->where(
                fn ($q) => $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('surname', 'like', "%{$this->search}%")
            ))
            ->paginate(50);
    }
}; ?>

<div class="flex flex-col gap-8">
    <flux:input wire:model.live="search" placeholder="Search members..."/>
    <flux:table :paginate="$this->players">
        <flux:table.columns>
            <flux:table.column>Name</flux:table.column>
            <flux:table.column sortable
                               :sorted="$sortBy === 'member_number'"
                               :direction="$sortDirection"
                               wire:click="sort('member_number')">#Nr.
            </flux:table.column>
            <flux:table.column sortable
                               :sorted="$sortBy === 'is_active'"
                               :direction="$sortDirection"
                               wire:click="sort('is_active')">Status
            </flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @foreach ($this->players as $player)
                <flux:table.row :key="$player->id">
                    <flux:table.cell class="flex items-center gap-3">
                        {{ $player->name }} {{ $player->surname }}
                    </flux:table.cell>

                    <flux:table.cell class="whitespace-nowrap">#{{ $player->member_number }}</flux:table.cell>

                    <flux:table.cell>
                        <flux:badge size="sm" color="{{ $player->is_active ? 'green' : 'amber' }}" inset="top bottom">
                            {{ $player->is_active ? 'Active' : 'Inactive' }}</flux:badge>
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal"
                                     inset="top bottom"></flux:button>
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>
</div>
