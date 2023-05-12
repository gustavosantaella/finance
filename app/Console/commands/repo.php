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
    protected $signature = 'make:repo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->ask('What is the name of the repository?');

        $path = 'app/Repositories/' . str_replace('\\', '/', $name. "Repository") . '.php';

        if (File::exists($path)) {
            $this->error('Repository already exists!');
            return;
        }

        File::ensureDirectoryExists(dirname($path));

        File::put($path, $this->buildClass($name));

        $this->info('Service created successfully!');
    }

    protected function buildClass($name)
    {
        $stub = File::get(__DIR__ . '/../../../stubs/repo.stub');

        $stub = str_replace('{{ class }}', $name . "Repository", $stub);

        return $stub;
    }
}
