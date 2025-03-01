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
        <div>
            <flux:input
                type="file"
                wire:model="form.bannerUpload"
                label="Banner"
                description="Will be shown on your event page"
            />
        </div>
        @if($form->bannerUpload)
            <img src="{{$form->bannerUpload->temporaryUrl()}}"
                 alt="Banner preview"
                 class="h-48 object-cover">
        @endif
    </div>
    <div class="flex gap-8 flex-wrap">
        <div>
            <flux:input
                type="file"
                wire:model="form.iconUpload"
                label="Icon"
                description="A small square picture for the event calendar"
            />
        </div>
        @if($form->iconUpload)
            <img src="{{$form->iconUpload->temporaryUrl()}}"
                 alt="Icon preview"
                 class="rounded-lg h-16 w-16 object-contain">
        @endif
    </div>
</div>
