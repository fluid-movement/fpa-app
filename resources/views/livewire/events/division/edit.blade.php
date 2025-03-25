<?php

use App\Enums\Division\DivisionSetupSteps;
use App\Models\Division;
use Livewire\Volt\Component;

new class extends Component
{
    public Division $division;

    public DivisionSetupSteps $step;

    public function rendering(Illuminate\View\View $view): void
    {
        $view->title('Edit Division | '.$this->division->type->getDisplayName());
    }

    public function mount(Division $division, DivisionSetupSteps $step = DivisionSetupSteps::Teams): void
    {
        $this->division = $division;
        $this->step = $step;
    }
}; ?>

<div>
    <x-back-button href="{{route('events.admin', [$division->event, \App\Enums\AdminTabs::Divisions])}}"/>
    <flux:radio.group wire:model.live="step" label="Steps" variant="cards" :indicator="false" class="max-sm:flex-col">
        @foreach(DivisionSetupSteps::cases() as $case)
            <flux:radio
                value="{{$case->value}}"
                label="{!! $case->getTitle() !!}"
                description="{{ $case->getDescription() }}"
                wire:click="$js.updateUrl('{{route('events.division.edit', [$division, $case])}}')"
            />
        @endforeach
    </flux:radio.group>
    <div class="mb-8"></div>
    <livewire:is
        :component="$step->getComponent()"
        :division="$division"
        wire:key="{{$step->getComponent()}}"
    />
</div>

@script
<script>
    $js('updateUrl', (url) => {
        console.log(url)
        window.history.pushState({}, '', url);
    })
</script>
@endscript
