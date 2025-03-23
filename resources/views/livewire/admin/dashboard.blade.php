<?php

use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;

new #[Title('Admin | Dashboard')] class extends Component
{
    public array $data = [];

    public function mount()
    {
        $this->data = $this->years;
    }

    #[Computed]
    public function members(): \Illuminate\Database\Eloquent\Collection
    {
        return \App\Models\Player::with('activeYears')->get();
    }

    #[Computed]
    public function years(): array
    {
        return $this->members
            ->flatMap(fn ($member) => $member->activeYears->map(fn ($year) => $year->year))
            ->countBy()
            ->sortKeys()
            ->map(fn ($count, $year) => [
                'year' => $year,
                'members' => $count,
            ])
            ->values()
            ->toArray();
    }
}; ?>
<div>
    <flux:text>
        Count of members: {{ $this->members->count() }}
    </flux:text>
    <flux:text>
        Active members: {{ $this->members->where('is_active', true)->count() }}
    </flux:text>
    <flux:chart :value="$this->years" class="aspect-3/1">
        <flux:chart.svg>
            <flux:chart.line field="members" class="text-blue-500 dark:text-blue-400" />

            <flux:chart.axis axis="x" field="year">
                <flux:chart.axis.line />
                <flux:chart.axis.tick />
            </flux:chart.axis>

            <flux:chart.axis axis="y">
                <flux:chart.axis.grid />
                <flux:chart.axis.tick />
            </flux:chart.axis>

            <flux:chart.cursor />
        </flux:chart.svg>

        <flux:chart.tooltip>
            <flux:chart.tooltip.heading field="year" :format="['year' => 'date']" />
            <flux:chart.tooltip.value field="members" label="Members" />
        </flux:chart.tooltip>
    </flux:chart>
</div>
