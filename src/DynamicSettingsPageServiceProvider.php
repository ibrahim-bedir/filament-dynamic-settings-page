<?php
namespace IbrahimBedir\FilamentDynamicSettingsPage;

use Filament\PluginServiceProvider;
use IbrahimBedir\FilamentDynamicSettingsPage\Pages\SettingPage;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;

class DynamicSettingsPageServiceProvider extends PluginServiceProvider
{
    public static string $name = 'filament-dynamic-settings-page';

    public function configurePackage(Package $package): void
    {
        parent::configurePackage($package);
        $package
            ->hasMigrations([
                'create_settings_table'
            ])
            ->hasTranslations()
            ->hasInstallCommand(function (InstallCommand $installCommand) {
                $installCommand
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('ibrahim-bedir/filament-dynamic-settings-page');
            })
            ->hasViews();
    }

    protected function getPages(): array
    {
        return [
            SettingPage::class
        ];
    }
}
