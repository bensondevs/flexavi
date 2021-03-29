<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RepositoryGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {name : Name of repository}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Making repository for design pattern';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    protected function getStub()
    {
        return file_get_contents(resource_path('stubs/Repository.stub'));
    }

    public function generateRepository($name)
    {
        $repositoryTemplate = str_replace([
                '{{repositoryName}}',
            ],
            [
                $name,
            ],
            $this->getStub('Repository')
        );

        file_exists(app_path("Repositories/{$name}.php")) ?
            $this->info($name . ' is already exist!') :
            file_put_contents(
                app_path("Repositories/{$name}.php"), 
                $repositoryTemplate
            );
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->argument('name');

        $this->generateRepository($name);
        $this->info($this->argument('name') . ' has created successfully!');
    }
}
