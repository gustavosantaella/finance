<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class apiController extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:apiController';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new api controller';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->ask('What is the name of the controller?');

        $path = 'app/Http/Controllers/Api/' . str_replace('\\', '/', $name) . '.php';

        if (File::exists($path)) {
            $this->error('Controller already exists!');
            return;
        }

        File::ensureDirectoryExists(dirname($path));

        File::put($path, $this->buildClass($name));

        $this->info('Controller created successfully!');
    }

    protected function buildClass($name)
    {
        $stub = File::get(__DIR__ . '/../../../stubs/api.controller.stub');

        $stub = str_replace('{{ class }}', $name, $stub);

        return $stub;
    }
}
