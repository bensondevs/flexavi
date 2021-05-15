<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateTraitClass extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:trait {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Trait to implement to class.';

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
        return file_get_contents(resource_path('stubs/Trait.stub'));
    }

    public function generateRepository($name)
    {
        $traitTemplate = str_replace([
                '{{traitName}}',
            ],
            [
                $name,
            ],
            $this->getStub()
        );

        if (! is_dir(app_path('Traits')))
            shell_exec('mkdir ' . app_path('Traits'));

        $exist = file_exists(app_path("Traits/{$name}.php"));
        
        if (! $exist) {
            file_put_contents(
                app_path("Traits/{$name}.php"), 
                $traitTemplate
            );

            return $this->info($this->argument('name') . ' has created successfully!');
        }

        return $this->error($name . ' is already exist!');
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->generateRepository($this->argument('name'));
    }
}
