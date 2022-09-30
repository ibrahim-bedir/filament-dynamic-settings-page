<?php

namespace IbrahimBedir\FilamentDynamicSettingsPage\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;
use IbrahimBedir\FilamentDynamicSettingsPage\Models\Setting as ModelsSetting;

class Setting extends Page
{

    use WithFileUploads;
    protected static string $view = 'filament-dynamic-settings-page::pages.setting';
    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static ?int $navigationSort = 1;

    protected static function getNavigationGroup(): ?string
    {
        return config('filament-dynamic-settings-page.navigation.group');
    }

    protected static function getNavigationIcon(): string
    {
        return config('filament-dynamic-settings-page.navigation.icon');
    }

    protected static function getNavigationSort(): ?int
    {
        return config('filament-dynamic-settings-page.navigation.sort');
    }

    protected static function getNavigationLabel(): string
    {
        return __('filament-dynamic-settings-page::settings-resource.navigation.label');
    }

    protected function getTitle(): string
    {
        return __('filament-dynamic-settings-page::settings-resource.title');
    }

    protected function getBreadcrumbs(): array
    {
        return __('filament-dynamic-settings-page::settings-resource.breadcrumbs');
    }

    public $form;
    public $settingGroups;
    public $tabs;
    public $activeTab;
    public $newSetting = [];

    public $inputClass = "block w-full transition duration-75 rounded-lg shadow-sm focus:border-primary-500 focus:ring-1 focus:ring-inset focus:ring-primary-500 disabled:opacity-70 dark:bg-gray-700 dark:text-white dark:focus:border-primary-500 border-gray-300 dark:border-gray-600";
    public $buttonClass = "filament-button inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset dark:focus:ring-offset-0 min-h-[2.25rem] px-4 text-sm text-white shadow focus:ring-white border-transparent bg-primary-600 hover:bg-primary-500 focus:bg-primary-700 focus:ring-offset-primary-700 filament-page-button-action";

    public function mount()
    {
        $settingGroups = ModelsSetting::all();
        $this->tabs = $settingGroups->pluck('group')->unique()->values();
        $this->settingGroups = $settingGroups->groupBy('group')->map(function ($value) {
            return $value->sortBy('order')->values();
        })->toArray();
        $this->form = $settingGroups->keyBy('id')->toArray();
        $this->activeTab = $this->activeTab ?? collect($this->tabs)->first();
    }

    public function changeGroup($group)
    {
        $this->newSetting['group'] = $group;
    }

    public function saveNewSetting()
    {
        $this->validate([
            'newSetting.display_name' => 'required',
            'newSetting.key' => 'required',
            'newSetting.type' => 'required',
            'newSetting.type' => 'required',
            'newSetting.group' => 'required',
        ]);
        ModelsSetting::insert($this->newSetting);
        Notification::make()
            ->title($this->savedNotificationMessage())
            ->success()
            ->send();
        $this->reset('newSetting');
        $this->mount();
    }

    public function selectedTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function save()
    {
        foreach ($this->form as $data) {
            if ($data['value'] instanceof \Livewire\TemporaryUploadedFile) {
                if ($oldImage = ModelsSetting::whereId($data['id'])->value('value')) {
                    Storage::disk('public')->delete($oldImage);
                }
                $data['value'] =  $data['value']->store('uploads/settings', 'public');
            }

            DB::table('settings')
                ->where('id', $data['id'])
                ->update($data);
        }

        Notification::make()
            ->title($this->savedNotificationMessage())
            ->success()
            ->send();
    }

    public function destroySetting($id)
    {
        $setting = ModelsSetting::whereId($id)->firstOrFail();
        if ($setting->type == 'image') {
            Storage::disk('public')->delete($setting->value);
        }
        $setting->delete();
        $this->mount();
        Notification::make()
            ->title($this->deletedNotificationMessage())
            ->success()
            ->send();
    }

    public function order($orders)
    {
        if (!$orders) {
            return;
        }
        foreach ($orders as $key => $order) {
            DB::table('settings')
                ->where('id', $key)
                ->update([
                    'order' => $order
                ]);
        }
        $this->mount();
        Notification::make()
            ->title($this->sortNotificationMessage())
            ->success()
            ->send();
    }

    protected function savedNotificationMessage(): ?string
    {
        return __('filament-dynamic-settings-page::settings-resource.notifications.saved');
    }

    protected function deletedNotificationMessage(): ?string
    {
        return __('filament-dynamic-settings-page::settings-resource.notifications.deleted');
    }

    protected function sortNotificationMessage(): ?string
    {
        return __('filament-dynamic-settings-page::settings-resource.notifications.order-sort');
    }
}
