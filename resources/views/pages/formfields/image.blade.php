<input wire:model.lazy="form.{{  $item['id'] }}.value" class="block w-full transition duration-75 rounded-lg shadow-sm focus:border-primary-500 focus:ring-1 focus:ring-inset focus:ring-primary-500 disabled:opacity-70 dark:bg-gray-700 dark:text-white dark:focus:border-primary-500 border-gray-300 dark:border-gray-600" type="file">
@if ($form[$item['id']]['value'] instanceof \Livewire\TemporaryUploadedFile)
    <span class="text-sm font-bold mt-1">{{ __('filament-dynamic-settings-page::settings-resource.preview') }}:</span>
    <img width="300" src="{{ $form[$item['id']]['value']->temporaryUrl() }}">
    @else
    <img width="300" class="mt-2" src="{{ asset($form[$item['id']]['value']) }}">
@endif