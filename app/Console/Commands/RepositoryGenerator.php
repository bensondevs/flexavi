<?php

namespace App\Console\Commands;

use App\Services\Utility\ClassGeneratorService;
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

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $repositoryName = $this->argument('name');

        $generatorService = (new ClassGeneratorService)
            ->setType('repository')
            ->setFileName($repositoryName);

        if ($exists = file_exists($generatorService->getFullDesignatedPath())) {
            $question = 'The class is already exist. Are you sure want to override the existing class?';
            if (!$this->confirm($question)) {
                $this->error('Class overriding process aborted.');
                return;
            }
        }

        $className = $generatorService->getClassName();
        $type = $exists ? 'overridden' : 'created';
        $generatorService->generate() ?
            $this->info($className . ' has been ' . $type . ' successfully!') :
            $this->error('Failed to generate the class! Please check permission');
    }
}
