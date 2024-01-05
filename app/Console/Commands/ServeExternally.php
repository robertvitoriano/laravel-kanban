<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ServeExternally extends Command
{
    protected $signature = 'serve:externally {--port= : The port to serve the application on.}';
    protected $description = 'Serve the application on the PHP built-in server externally';

    public function handle()
    {
        $port = $this->option('port') ?: 8000;

        $this->info("Laravel development server started on http://0.0.0.0:{$port}");

        passthru(PHP_BINARY." artisan serve --host=0.0.0.0 --port={$port}");
    }
}
