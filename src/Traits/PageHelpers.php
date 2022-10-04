<?php

namespace IbrahimBedir\FilamentDynamicSettingsPage\Traits;

use Filament\Notifications\Notification;
use IbrahimBedir\FilamentDynamicSettingsPage\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Livewire\TemporaryUploadedFile;

trait PageHelpers
{
    /**
     * Validation rules
     *
     * @var array
     */
    protected $rules = [
        'newSetting.display_name' => 'required',
        'newSetting.key' => 'required',
        'newSetting.type' => 'required',
        'newSetting.type' => 'required',
        'newSetting.group' => 'required',
    ];

    /**
     * @param mixed $file
     * @return boolean
     */
    protected function hasFileUpload($file): bool
    {
        return $file instanceof \Livewire\TemporaryUploadedFile;
    }

    /**
     * @param array $data
     * @return string
     */
    protected function saveFile(Setting $setting, TemporaryUploadedFile $file): string
    {
        if (filled($setting->value)) {
            Storage::disk('public')->delete($setting->value);
        }

        return $file->store('uploads/settings', 'public');
    }

    /**
     * @param string $message
     * @param string $type
     * @return void
     */
    protected function sendNotification(string $message, $type = 'success'): void
    {
        Notification::make()
            ->title($message)
            ->{$type}()
            ->send();
    }

    /**
     * @return string|null
     */
    protected function savedNotificationMessage(): ?string
    {
        return __('filament-dynamic-settings-page::settings-resource.notifications.saved');
    }

    /**
     * @return string|null
     */
    protected function deletedNotificationMessage(): ?string
    {
        return __('filament-dynamic-settings-page::settings-resource.notifications.deleted');
    }

    /**
     * @return string|null
     */
    protected function sortNotificationMessage(): ?string
    {
        return __('filament-dynamic-settings-page::settings-resource.notifications.order-sort');
    }

    /**
     * @return string|null
     */
    protected static function getNavigationGroup(): ?string
    {
        return config('filament-dynamic-settings-page.navigation.group');
    }

    /**
     * @return string
     */
    protected static function getNavigationIcon(): string
    {
        return config('filament-dynamic-settings-page.navigation.icon');
    }

    /**
     * @return integer|null
     */
    protected static function getNavigationSort(): ?int
    {
        return config('filament-dynamic-settings-page.navigation.sort');
    }

    /**
     * @return string
     */
    protected static function getNavigationLabel(): string
    {
        return config('filament-dynamic-settings-page.navigation.label');
    }

    /**
     * @return string
     */
    protected function getTitle(): string
    {
        return config('filament-dynamic-settings-page.title');
    }

    /**
     * @return array
     */
    protected function getBreadcrumbs(): array
    {
        return config('filament-dynamic-settings-page.breadcrumbs');
    }
}
