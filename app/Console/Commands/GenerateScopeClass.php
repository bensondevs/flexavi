<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateScopeClass extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:scope {scope}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Making scope class for model';

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
        $scopeName = $this->argument('scope');

        // Create `app/Scopes` folder if does not exist
        $scopeBasePath = app_path('Scopes');
        if (! file_exists($scopeBasePath)) {
            shell_exec('mkdir ' . $scopeBasePath);
        }

        // Check file exist in scope folders
        $scopeFile = $scopeBasePath . $scopeName;
        if (file_exists($scopeFile . '.php')) {
            return $this->error('Documentation already exists.');
        }

        // Break input to several pieces
        $explode = explode('/', $scopeName);

        // Collect file name
        $scopeFileName = $explode[count($explode) - 1] . '.php';

        // Is there extended path?
        if (count($explode) > 1) {
            // Collect extended path
            unset($explode[count($explode) - 1]);
            $extendedPath = implode('/', $explode);
            $scopeBasePath = $scopeBasePath . '/' . $extendedPath;
        }

        $scopeBasePath = $scopeBasePath . '/' . $scopeFileName;

        // Fetch to template
        $templatePath = resource_path('stubs/Scope.stub');
        $template = file_get_contents($templatePath);
        $content = str_replace('{{scopeName}}', $scopeName, $template);

        if (! file_put_contents($scopeBasePath, $content)) {
            return $this->error('Failed to create scope class file to folder.');
        }

        return $this->info('Scope has been created successfully');
    }
}
