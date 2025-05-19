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
            wire:model="form.pictureUpload"
            label="Picture"
            description="Will be shown on your event page (on larger screens)"
        />
        @if($form->pictureUpload)
            <img src="{{$form->pictureUpload->temporaryUrl()}}"
                 alt="Picture preview"
                 class="h-48 object-cover">
        @endif
    </div>
</div>
