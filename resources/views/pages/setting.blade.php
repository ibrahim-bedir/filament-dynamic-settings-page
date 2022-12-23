<x-filament::page>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.2.1/dist/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
        .select2 .select2-container {
            width: 100% !important;
        }

        .select2-selection {
            background-color: #fff !important;
            border: 1px solid #aaaaaab3 !important;
            border-radius: 0.5rem !important;
            padding: 20px !important;
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
        }

        .select2-selection__arrow {
            height: 100% !important;
        }

        .has-error .select2-selection {
            border-color: rgb(244 63 94 / var(--tw-border-opacity)) !important;
        }
    </style>
    <div class="grid md:grid-cols-4 grid-cols-1 w-full gap-3">
        @foreach ($tabs as $key => $group)
            <button wire:key="{{ $key }}" wire:click="selectedTab('{{ $group }}')"
                class="@if ($activeTab === $group) bg-gray-200 dark:bg-gray-700 border-gray-700 @endif flex bg-white hover:bg-gray-100 dark:bg-gray-800 border my-2 items-center focus:outline-none">
                <div
                    class="flex items-center justify-center w-5 h-14 ml-4 @if ($activeTab === $group) !bg-white dark:bg-transparent @endif bg-transparent font-semibold text-black">
                    {{ ++$key }}</div>
                <div class="flex items-center h-full pl-4">
                    <span class="text-base font-semibold">{{ $group }}</span>
                </div>
            </button>
        @endforeach
    </div>
    @foreach ($settingGroups as $group => $items)
        @if ($activeTab === $group)
            <div wire:key="{{ $group }}"
                class="filament-forms-card-component p-6 bg-white rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-800">
                <h2 class="text-xl font-bold">{{ $group }}</h2>
                <div id="sortable">
                    @foreach ($items as $key => $item)
                        <div wire:key="{{ $group . $key }}" data-id="{{ $item['id'] }}"
                            class="flex flex-col list-setting mt-2 mb-2 gap-2">
                            <label
                                class="text-sm font-medium leading-4 text-gray-700 dark:text-gray-300 mb-2 flex justify-between items-center">
                                <div class="flex items-center">
                                    {{ $item['display_name'] }}
                                    <span class="handle">
                                        <x-heroicon-o-selector class="w-5 h-5 cursor-move" />
                                    </span>
                                </div>
                                @if(config('filament-dynamic-settings-page.tool.enable'))
                                <button wire:loading.attr="disabled" wire:loading.class="!bg-primary-200"
                                    wire:target="destroySetting">
                                    <x-heroicon-o-trash
                                        onclick="return confirm('{{ __('filament-dynamic-settings-page::settings-resource.delete.confirm') }}') ? Livewire.emit('destroySetting',{{ $item['id'] }}) : false"
                                        class="w-5 h-5 hover:text-danger-500 cursor-pointer" />
                                </button>
                                @endif
                            </label>
                            @includeIf('filament-dynamic-settings-page::pages.formfields.' . $item['type'], [
                                'wireModel' => 'wire:model.lazy=form.' . $item['id'] . '.value',
                                'photo' => $item['type'] == 'image' ? $form[$item['id']]['value'] : '',
                            ])
                        </div>
                    @endforeach
                </div>
                <button wire:loading.attr="disabled" wire:loading.class="!bg-primary-200" wire:target="save"
                    wire:click="save" class="{{ $buttonClass }} mt-2 mb-2">
                    {{ __('filament-dynamic-settings-page::settings-resource.save.button') }}
                </button>
            </div>
        @endif
    @endforeach
    @if(config('filament-dynamic-settings-page.tool.enable'))
    <div
        class="filament-forms-card-component p-6 bg-white rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-800">
        <h2 class="text-xl font-bold">
            {{ __('filament-dynamic-settings-page::settings-resource.fields.card-header') }}</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-2">
            <input wire:model.lazy="newSetting.display_name" type="text"
                placeholder="{{ __('filament-dynamic-settings-page::settings-resource.fields.name') }}"
                class="{{ $inputClass }} @error('newSetting.display_name') !border-danger-500 @enderror">
            <input wire:model.lazy="newSetting.key" type="text"
                placeholder="{{ __('filament-dynamic-settings-page::settings-resource.fields.key') }}"
                class="{{ $inputClass }} @error('newSetting.key') !border-danger-500 @enderror">
            <select wire:model.lazy="newSetting.type"
                class="{{ $inputClass }} @error('newSetting.type') !border-danger-500 @enderror">
                <option value="">
                    {{ __('filament-dynamic-settings-page::settings-resource.fields.types.default') }}</option>
                <option value="text">{{ __('filament-dynamic-settings-page::settings-resource.fields.types.text') }}
                </option>
                <option value="text_area">
                    {{ __('filament-dynamic-settings-page::settings-resource.fields.types.text_area') }}</option>
                <option value="image">{{ __('filament-dynamic-settings-page::settings-resource.fields.types.image') }}
                </option>
            </select>
            <div @error('newSetting.group') class="has-error" @enderror>
                <select wire:model.lazy="newSetting.group" id="multiple" class="{{ $inputClass }}">
                    <option value="" selected="selected">
                        {{ $newSetting['group'] ?? __('filament-dynamic-settings-page::settings-resource.fields.group') }}
                    </option>
                    @if ($tabs)
                        @foreach ($tabs as $item)
                            <option>{{ $item }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
        <div align="right">
            <button wire:loading.attr="disabled" wire:loading.class="!bg-primary-200" wire:target="saveNewSetting"
                wire:click="saveNewSetting" class="mt-2 mb-2 {{ $buttonClass }}">
                {{ __('filament-dynamic-settings-page::settings-resource.add.button') }}
            </button>
        </div>
    </div>
    @endif
    <script>
        function initSelect2() {
            $("#multiple").select2({
                tags: true
            })
        }

        function setSortable() {
            $("#sortable").sortable({
                placeholder: "ui-state-highlight",
                handle: ".handle",
                stop: function(e, ui) {
                    let order = {};
                    $('.list-setting').each(function(ind, el) {
                        order[$(this).data('id')] = $(this).index();
                    });
                    Livewire.emit('order', order)
                }
            });
        }
        document.addEventListener("DOMContentLoaded", () => {
            initSelect2()

            $("#multiple").on("change", function(e) {
                Livewire.emit('changeGroup', e.target.value)
            });

            setSortable();

            Livewire.hook('message.processed', (message, component) => {
                setSortable();
                initSelect2()
            })
        });
    </script>
</x-filament::page>
