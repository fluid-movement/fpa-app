<?php

use App\Models\Event;
use Livewire\Volt\Component;

new class extends Component
{
    public Event $event;
}; ?>

<div>
    <flux:heading size="lg" class="mb-4">Attending Jammers</flux:heading>
    <flux:table>
        <flux:table.columns>
            <flux:table.column>Name</flux:table.column>
            <flux:table.column>Updated</flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @foreach ($event->attending as $user)
                <flux:table.row :key="$user->id">
                    <flux:table.cell>
                        {{ $user->name }}
                    </flux:table.cell>
                    <flux:table.cell>
                        {{ $user->pivot->updated_at->diffForHumans() }}
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>
</div>
