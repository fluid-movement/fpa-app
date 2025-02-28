<div class="flex flex-col gap-8 my-8">
    <flux:input
        label="Name"
        wire:model.blur="form.name"
    />
    <flux:input
        label="Location"
        wire:model="form.location"
    />
    <flux:date-picker
        label="Date"
        mode="range"
        wire:model="form.dateRange"
        description="Select start and end dates"
    />
    <flux:editor
        label="Description"
        wire:model="form.description"
        description="Write something about the event"
        toolbar="heading | bold italic underline | link | bullet ordered | undo redo"
    />
</div>
