<?php

use Livewire\Attributes\Computed;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new #[\Livewire\Attributes\Title('FPA Members')] class extends Component
{
    use WithPagination;

    public string $search = '';

    #[Computed]
    public function players(): \Illuminate\Pagination\LengthAwarePaginator
    {
        return \App\Models\Player::with('activeYears')
            ->when($this->search, fn ($query) => $query->where(
                fn ($q) => $q->where('name', 'ILIKE', "%{$this->search}%")
                    ->orWhere('surname', 'ILIKE', "%{$this->search}%")
            ))
            ->paginate(20);
    }

    public function isActive(\App\Models\Player $player): bool
    {
        return $player->activeYears->isNotEmpty();
    }
}; ?>

<div class="flex flex-col gap-8">
    <flux:input wire:model.live="search" placeholder="Search members..."/>
    <flux:text>todo: add filter buttons</flux:text>
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
                        <flux:badge size="sm"
                                    color="{{ $player->is_active ? 'green' : 'amber' }}">
                            {{ $player->is_active ? 'Active' : 'Inactive' }}
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
                                <flux:menu.item icon="cog-6-tooth" class="cursor-pointer" href="{{route('admin.members.edit', $player)}}">Manage</flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>
</div>
