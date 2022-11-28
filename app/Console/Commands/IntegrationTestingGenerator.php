<?php

namespace App\Console\Commands;

use App\Services\Utility\ClassGeneratorService;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class IntegrationTestingGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:integration-testing {name : Name of the Integration Database}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new integration testing';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $testName = $this->argument('name');

        $generatorTest = (new ClassGeneratorService)->setType('integration_test')->setFileName($testName);

        if ($exists = file_exists($generatorTest->getFullDesignatedPath())) {
            $question = 'The class is already exist. Are you sure want to override the existing class?';
            if (!$this->confirm($question)) {
                $this->error('Class overriding process aborted.');
                return 0;
            }
        }

        $className = $generatorTest->getClassName();
        $type = $exists ? 'overridden' : 'created';
        $generatorTest->generate() ?
            $this->info($className . ' has been ' . $type . ' successfully!') :
            $this->error('Failed to generate the class! Please check permission');

        return CommandAlias::SUCCESS;

    }
}
