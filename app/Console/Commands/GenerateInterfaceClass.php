<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateInterfaceClass extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:interface {interface}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generating interface class for any class that should implement some functions';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $interfaceName = $this->argument('interface');

        // Create `app/Interfaces` folder if does not exist
        $interfaceBasePath = app_path('Interfaces');
        if (! file_exists($interfaceBasePath)) {
            shell_exec('mkdir ' . $interfaceBasePath);
        }

        // Check file exist in interface folders
        $interfaceFile = $interfaceBasePath . $interfaceName;
        if (file_exists($interfaceFile . '.php')) {
            return $this->error('Documentation already exists.');
        }

        // Break input to several pieces
        $explode = explode('/', $interfaceName);

        // Collect file name
        $interfaceFileName = $explode[count($explode) - 1] . '.php';

        // Is there extended path?
        if (count($explode) > 1) {
            // Collect extended path
            unset($explode[count($explode) - 1]);
            $extendedPath = implode('/', $explode);
            $interfaceBasePath = $interfaceBasePath . '/' . $extendedPath;
        }

        $interfaceBasePath = $interfaceBasePath . '/' . $interfaceFileName;

        // Fetch to template
        $templatePath = resource_path('stubs/Interface.stub');
        $template = file_get_contents($templatePath);
        $content = str_replace('{{interfaceName}}', $interfaceName, $template);

        if (! file_put_contents($interfaceBasePath, $content)) {
            return $this->error('Failed to create interface class file to folder.');
        }

        return $this->info('Interface has been created successfully');
    }
}
