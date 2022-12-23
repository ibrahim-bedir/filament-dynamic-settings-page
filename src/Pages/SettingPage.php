<?php

namespace IbrahimBedir\FilamentDynamicSettingsPage\Pages;

use Filament\Facades\Filament;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Resources\Form;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use IbrahimBedir\FilamentDynamicSettingsPage\Models\Setting;
use IbrahimBedir\FilamentDynamicSettingsPage\Traits\PageHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;

class SettingPage extends Page
{
    use WithFileUploads, PageHelpers, AuthorizesRequests;
    public $inputClass = 'block w-full transition duration-75 rounded-lg shadow-sm focus:border-primary-500 focus:ring-1 focus:ring-inset focus:ring-primary-500 disabled:opacity-70 dark:bg-gray-700 dark:text-white dark:focus:border-primary-500 border-gray-300 dark:border-gray-600';
    public $buttonClass = 'filament-button inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset dark:focus:ring-offset-0 min-h-[2.25rem] px-4 text-sm text-white shadow focus:ring-white border-transparent bg-primary-600 hover:bg-primary-500 focus:bg-primary-700 focus:ring-offset-primary-700 filament-page-button-action';

    protected static ?string $slug = 'settings';
    protected static string $view = 'filament-dynamic-settings-page::pages.setting';

    public $form;
    public $settingGroups;
    public $tabs;
    public $activeTab;
    public $newSetting = [];

    public $listeners = [
        'changeGroup' => 'changeGroup',
        'order' => 'order',
        'destroySetting' => 'destroySetting',

    ];

    protected static function shouldRegisterNavigation(): bool
    {
        if (config('filament-dynamic-settings-page.permission.enable')) {
            return Gate::any(config('filament-dynamic-settings-page.permission.name'));
        }
        return true;
    }

    public function mount()
    {
        if (config('filament-dynamic-settings-page.permission.enable')) {
            $this->authorize(config('filament-dynamic-settings-page.permission.name'), Setting::class);
        }

        $this->loadData();
    }

    /**
     * @return void
     */
    private function loadData(): void
    {

        $settingGroups = Setting::all();
        $this->tabs = $settingGroups->pluck('group')->unique()->values();
        $this->settingGroups = $settingGroups->groupBy('group')->map(function ($value) {
            return $value->sortBy('order')->values();
        })->toArray();
        $this->form = $settingGroups->keyBy('id')->toArray();
        $this->activeTab = $this->activeTab ?? collect($this->tabs)->first();
    }

    /**
     * @param string $group
     * @return void
     */
    public function changeGroup(string $group): void
    {
        $this->newSetting['group'] = $group;
    }

    /**
     * @return void
     */
    public function saveNewSetting(): void
    {
        $this->validate();

        Setting::insert($this->newSetting);

        $this->reset('newSetting');
        $this->sendNotification($this->savedNotificationMessage());
        $this->loadData();
    }

    /**
     * @param string $tab
     * @return void
     */
    public function selectedTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    /**
     * @return void
     */
    public function save(): void
    {
        foreach ($this->form as $data) {
            $setting = Setting::findOrFail($data['id']);

            if ($this->hasFileUpload($data['value'])) {
                $data['value'] = $this->saveFile($setting, $data['value']);
            }

            $setting->update($data);
        }

        $this->sendNotification($this->savedNotificationMessage());
    }

    /**
     * @param array $setting
     * @return void
     */
    public function destroySetting(int $settingId): void
    {
        $setting = Setting::findOrFail($settingId);

        if ($setting->type == 'image') {
            Storage::disk('public')->delete($setting->value);
        }

        $setting->delete();

        $this->loadData();
        $this->sendNotification($this->deletedNotificationMessage());
    }

    /**
     * @param array $orders
     * @return void
     */
    public function order(array $orders): void
    {
        foreach ($orders as $key => $order) {
            Setting::where('id', $key)->update([
                'order' => $order
            ]);
        }

        $this->loadData();
        $this->sendNotification($this->sortNotificationMessage());
    }
}
