<div class="flex flex-col gap-8 mb-8">
    <div class="flex flex-col gap-8 md:w-1/2">
        <flux:input label="Name" wire:model="form.name"/>
        <flux:input label="Surname" wire:model="form.surname"/>
        <flux:input label="Email" wire:model="form.email"/>
        <flux:input type="number" label="Membership Number" wire:model="form.member_number"/>
        <flux:input type="number" label="Year of birth" wire:model="form.year_of_birth"/>
        <flux:input label="Gender" wire:model="form.gender"/>
        <flux:input label="Country" wire:model="form.country"/>
        <flux:input label="City" wire:model="form.city"/>
        <flux:input type="number" label="Freestyling since (year)" wire:model="form.freestyling_since"/>
        <flux:input label="Notes" wire:model="form.notes"/>
    </div>
</div>
