<?php

namespace Spatie\Backup\Commands;

use Illuminate\Console\Command;
use Spatie\Backup\BackupDestination\BackupDestinationFactory;
use Spatie\Backup\Tasks\Cleanup\CleanupJob;

class CleanupCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'backup:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove all backups older than specified number of days in config.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $config = config('laravel-backup');

        $backupDestination = BackupDestinationFactory::createFromArray($config['backup']['destination']);

        $strategy = app($config['cleanup']['strategy']);

        $cleanupJob = new CleanupJob($backupDestination, $strategy);

        $cleanupJob->run();
    }

}