<?php

namespace Orion\Larashared;

use Orion\Larashared\Console\InstallHtaccessCommand;
use Orion\Larashared\Console\TokenGenerateCommand;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LarasharedServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('larashared')
            ->hasConfigFile()
            ->hasRoutes('api')
            ->hasCommands([
                TokenGenerateCommand::class,
                InstallHtaccessCommand::class,
            ])
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->startWith(function (InstallCommand $installCommand) {
                        $installCommand->call('larashared:htaccess');
                        $installCommand->call('larashared:token');
                        $installCommand->info('Thank you very much for installing this package!');
                    })
                    ->askToStarRepoOnGitHub('oriononairdev/larashared');
            });
    }
}
