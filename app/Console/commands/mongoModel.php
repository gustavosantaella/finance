<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class mongoModel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:mongoModel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new mongo model';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->ask('What is the name of the Model?');
        $collection = $this->ask('What is the collection?');

        $path = 'app/Models/' . str_replace('\\', '/', $name) . '.php';

        if (File::exists($path)) {
            $this->error('Repository already exists!');
            return;
        }

        File::ensureDirectoryExists(dirname($path));

        File::put($path, $this->buildClass($name, $collection));

        $this->info('Service created successfully!');
    }

    protected function buildClass($name, $collection)
    {
        $stub = File::get(__DIR__ . '/../../../stubs/mongo.model.stub');

        $stub = str_replace('{{ class }}', $name , $stub);
        $stub = str_replace('{{ collection }}', $collection , $stub);

        return $stub;
    }
}
