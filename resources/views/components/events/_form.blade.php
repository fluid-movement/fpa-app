<div class="flex flex-col gap-8 my-8">
    <flux:input
        label="Event name"
        wire:model.blur="form.name"
    />
    <div class="flex gap-8 flex-wrap">
        <flux:input
            label="Location"
            wire:model="form.location"
        />
        <flux:date-picker
            label="Date"
            mode="range"
            wire:model="form.dateRange"
        />
    </div>
    <flux:editor
        label="Description"
        wire:model="form.description"
        description="Write something about the event"
        toolbar="heading | bold italic underline | link | bullet ordered | undo redo"
    />
    <div class="flex gap-8 flex-wrap">
        <flux:input
            type="file"
            wire:model="banner"
            label="Banner"
            description="Will be shown on your event page"
        />
        <flux:input
            type="file" wire:model="icon"
            label="Icon"
            description="A small square image for the calendar"
        />
    </div>
</div>
