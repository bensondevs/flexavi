<?php

namespace App\Console\Commands;

use App\Services\Utility\ClassGeneratorService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class MakeHelper extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:helper {helper : The helper name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to generate helper file and register it to composer JSON.';

    /**
     * Register helper into the composer JSON content.
     *
     * @param string $helperName
     * @return void
     * @see \Tests\Unit\Commands\MakeHelper\RegisterHelperTest
     *      To the method unit tester class.
     */
    public function registerHelper(string $helperName): void
    {
        $composerJsonPath = base_path('composer.json');
        $composerJsonContent = file_get_contents($composerJsonPath);
        $composerJsonArray = json_decode($composerJsonContent, true);

        // Insert the newly created helper
        $helperPath = 'app/Helpers/' . $helperName . '.php';
        $composerJsonArray['autoload-dev']['files'][] = $helperPath;

        $newComposerJsonContent = json_encode(
            $composerJsonArray,
            JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
        );
        file_put_contents(
            $composerJsonPath,
            $newComposerJsonContent,
        );

        Artisan::call('optimize');
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $helperName = $this->argument('helper');

        $generatorService = (new ClassGeneratorService)
            ->setType('helper')
            ->setFileName($helperName);

        if ($exists = file_exists($generatorService->getFullDesignatedPath())) {
            $question = 'The class is already exist. Are you sure want to override the existing helper file?';
            if (! $this->confirm($question)) {
                $this->error('Class overriding process aborted.');
                return 0;
            }
        }

        $this->registerHelper($helperName);

        $className = $generatorService->getClassName();
        $type = $exists ? 'overridden' : 'created';
        $generatorService->generate() ?
            $this->info($className . ' has been ' . $type . ' successfully!') :
            $this->error('Failed to generate the helper file! Please check permission');

        return 0;
    }
}
