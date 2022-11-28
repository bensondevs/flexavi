<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateController extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flexavi:controller {controller : Class (singular) for example UserController}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate special modified controller that implements repository.';

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
        $controller = $this->argument('controller');

        // Check if file is exists
        $controllerPath = app_path('Http/Controllers/' . $controller . '.php');
        if (file_exists($controllerPath)) {
            return $this->error('Controller already exists.');
        }

        // Get controller name
        $explode = explode($controllerPath);
        $controllerFileName = $explode[count($explode) - 1] . '.php';
        $controllerName = str_replace('.php', '', $controllerFileName);
        $baseControllerName = str_replace('Controller', '', $controllerName);
        $directory = str_replace($controllerFileName, '', $controllerPath);

        // Preparing template
        $templatePath = resource_path('stubs/Controller.stub');
        $controllerTemplate = file_get_contents($templatePath);

        // Process the template
        $content = str_replace(
            ['{{baseControllerName}}', '{{controllerName}}'],
            [$baseControllerName, $controllerName],
            $controllerTemplate
        );

        return $this->info('Controller successfully created.');
    }
}
