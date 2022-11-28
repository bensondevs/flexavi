<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeDocumentation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:documentation {model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Making documentation for certain model';

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
        $model = $this->argument('model');

        // Check file exist in documentation
        $filePath = base_path('docs/' . $model) . '.md';
        if (file_exists($filePath)) {
            return $this->error('Documentation already exists.');
        }

        // Get model name
        $explode = explode('/', $model);
        $modelFileName = $explode[count($explode) - 1] . '.md';
        $modelName = str_replace('.md', '', $modelFileName);
        $modelNameLowerCase = strtolower($modelName);
        $directory = str_replace($modelFileName, '', $filePath);

        if (! file_exists($directory)) {
            shell_exec('mkdir ' . $directory);
        }

        // Preparing Template
        $documentationTemplate = file_get_contents(resource_path('stubs/Documentation.stub'));

        // Replace Template with desired data
        $content = str_replace(
            ['{{modelName}}', '{{modelNameLowerCase}}'], 
            [$modelName, $modelNameLowerCase], 
            $documentationTemplate
        );
        
        // Put content as documentation file
        if (! file_put_contents($filePath, $content)) {
            return $this->error('Failed to generate documentation to folder.');
        }

        $this->info(str_replace('.md', '', $modelName) . ' has been created successfully!');
    }
}
