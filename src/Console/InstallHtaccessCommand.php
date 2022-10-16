<?php

namespace Orion\Larashared\Console;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'larashared:htaccess')]
class InstallHtaccessCommand extends Command
{
    use ConfirmableTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'larashared:htaccess
                    {--force : Force the operation to run when in production}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copy default htaccess to base path';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        if (! $this->confirmToProceed()) {
            return;
        }

        copy(__DIR__.'/../../stubs/.htaccess', base_path('.htaccess'));

        $this->components->info('Default htaccess file copied successfully.');
    }
}
