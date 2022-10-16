<?php

namespace Orion\Larashared\Console;

use Illuminate\Console\ConfirmableTrait;
use Illuminate\Encryption\Encrypter;
use Illuminate\Foundation\Console\KeyGenerateCommand;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'larashared:token')]
class TokenGenerateCommand extends KeyGenerateCommand
{
    use ConfirmableTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'larashared:token
                    {--show : Display the token instead of modifying files}
                    {--force : Force the operation to run when in production}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set the Larashared token';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $token = $this->generateRandomToken();

        if ($this->option('show')) {
            $this->line('<comment>'.$token.'</comment>');

            return;
        }

        if (! $this->setTokenInEnvironmentFile($token)) {
            return;
        }

        $this->laravel['config']['larashared.token'] = $token;

        $this->components->info('Larashared token set successfully.');
    }

    /**
     * Generate a random token for the api.
     *
     * @return string
     */
    protected function generateRandomToken(): string
    {
        return base64_encode(Encrypter::generateKey($this->laravel['config']['app.cipher']));
    }

    /**
     * Set the Larashared token in the environment file.
     *
     * @param  string  $token
     * @return bool
     */
    protected function setTokenInEnvironmentFile(string $token): bool
    {
        $currentToken = $this->laravel['config']['larashared.token'];

        if (strlen($currentToken) !== 0 && (! $this->confirmToProceed())) {
            return false;
        }

        $this->writeNewEnvironmentFileWith($token);

        return true;
    }

    /**
     * Write a new environment file with the given token.
     *
     * @param  string  $token
     * @return void
     */
    protected function writeNewEnvironmentFileWith($token): void
    {
        file_put_contents($this->laravel->environmentFilePath(), preg_replace(
            $this->tokenReplacementPattern(),
            'LARASHARED_TOKEN='.$token,
            file_get_contents($this->laravel->environmentFilePath())
        ));
    }

    /**
     * Get a regex pattern that will match env LARASHARED_TOKEN with any random token.
     *
     * @return string
     */
    protected function tokenReplacementPattern(): string
    {
        $escaped = preg_quote('='.$this->laravel['config']['larashared.token'], '/');

        return "/^LARASHARED_TOKEN{$escaped}/m";
    }
}
