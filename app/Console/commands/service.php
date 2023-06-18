<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class service extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->ask('What is the name of the service?');

        $path = 'app/Services/' . str_replace('\\', '/', $name) . '.php';

        if (File::exists($path)) {
            $this->error('Service already exists!');
            return;
        }

        File::ensureDirectoryExists(dirname($path));

        File::put($path, $this->buildClass($name));

        $this->info('Service created successfully!');
    }

    protected function buildClass($name)
    {
        $stub = File::get(__DIR__ . '/../../../stubs/service.stub');

        $stub = str_replace('{{ class }}', $name, $stub);

        return $stub;
    }
}
