<div class="flex flex-col gap-8 mb-8">
    <div class="flex flex-col gap-8 md:w-1/2">
        <flux:input label="Event name" wire:model.blur="form.name"/>
        <div class="flex gap-8 flex-wrap md:flex-nowrap">
            <flux:input label="Location" wire:model="form.location"/>
            <flux:date-picker
                with-confirmation="true"
                label="Date"
                mode="range"
                wire:model="form.dateRange"
            />
        </div>
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
            wire:model="form.bannerUpload"
            label="Banner"
            description="Will be shown on your event page"
        />
        @if($form->bannerUpload)
            <img src="{{$form->bannerUpload->temporaryUrl()}}"
                 alt="Banner preview"
                 class="h-48 object-cover">
        @endif
    </div>
    <div class="flex gap-8 flex-wrap">
        <flux:input
            type="file"
            wire:model="form.iconUpload"
            label="Icon"
            description="A small square picture for the event calendar"
        />
        @if($form->iconUpload)
            <img src="{{$form->iconUpload->temporaryUrl()}}"
                 alt="Icon preview"
                 class="rounded-lg h-16 w-16 object-contain">
        @endif
    </div>
</div>
