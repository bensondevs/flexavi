<?php

namespace App\Services\Utility;

class ClassGeneratorService
{
    /**
     * Directory location based on the type.
     *
     * @var array
     */
    private array $basePaths = [];

    /**
     * Stub paths for the class.
     *
     * @var array
     */
    private array $stubPaths = [];

    /**
     * Stub variables based on type
     *
     * @var array
     */
    private array $stubCompacts = [];

    /**
     * Extended path of the generated class.
     *
     * @var string|null
     */
    private ?string $extendedPath = null;

    /**
     * Class name of the generated class.
     *
     * @var string
     */
    private string $className = '';

    /**
     * Type of the generated class.
     *
     * @var string
     */
    private string $type = 'service';

    /**
     * Create New Service Instance
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Set file name of the generated class.
     *
     * This will guess the subdirectory of the generated class.
     *
     * @param string $fileName
     * @return $this
     */
    public function setFileName(string $fileName): self
    {
        $explode = explode('/', $fileName);
        $nameIndex = count($explode) - 1; // last index
        $this->className = $explode[$nameIndex];
        unset($explode[$nameIndex]);
        if (count($explode) > 0) {
            $this->extendedPath = implode('/', $explode);
        }

        $this->prepareVariables();

        return $this;
    }

    /**
     * Prepare class generator variables.
     *
     * @return void
     */
    private function prepareVariables(): void
    {
        $this->basePaths = [
            'service' => app_path('Services'),
            'repository' => app_path('Repositories'),
            'contract' => app_path('Contracts'),
            'trait' => app_path('Traits'),
            'scope' => app_path('Scopes'),
            'enum' => app_path('Enums'),
            'integration_test' => test_path('Integration'),
        ];

        $this->stubPaths = [
            'service' => stub_path('Service.stub'),
            'repository' => stub_path('Repository.stub'),
            'contract' => stub_path('Contract.stub'),
            'trait' => stub_path('Trait.stub'),
            'scope' => stub_path('Scope.stub'),
            'enum' => stub_path('Enum.stub'),
            'integration_test' => stub_path('IntegrationTest.stub'),
        ];

        $this->stubCompacts = [
            'service' => [
                '{{serviceName}}' => $this->getClassName(),
                '{{extendedPath}}' => $this->getExtendedPath() ?
                    '\\' . $this->getExtendedPath() : '',
            ],
            'repository' => [
                '{{repositoryName}}' => $this->getClassName(),
                '{{extendedPath}}' => $this->getExtendedPath() ?
                    '\\' . $this->getExtendedPath() : '',
            ],
            'contract' => [
                '{{contractName}}' => $this->getClassName(),
                '{{extendedPath}}' => $this->getExtendedPath() ?
                    '\\' . $this->getExtendedPath() : '',
            ],
            'trait' => [
                '{{traitName}}' => $this->getClassName(),
                '{{extendedPath}}' => $this->getExtendedPath() ?
                    '\\' . $this->getExtendedPath() : '',
            ],
            'scope' => [
                '{{scopeName}}' => $this->getClassName(),
                '{{extendedPath}}' => $this->getExtendedPath() ?
                    '\\' . $this->getExtendedPath() : '',
            ],
            'enum' => [
                '{{enumName}}' => $this->getClassName(),
                '{{extendedPath}}' => $this->getExtendedPath() ?
                    '\\' . $this->getExtendedPath() : '',
            ],
            'integration_test' => [
                '{{testName}}' => $this->getClassName(),
                '{{extendedPath}}' => $this->getExtendedPath() ?
                    '\\' . $this->getExtendedPath() : '',
            ],
        ];
    }

    /**
     * Get class name of the generated class
     *
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * Get extended path of the generated class.
     *
     * @return string
     */
    public function getExtendedPath(): string
    {
        $basePath = $this->getDirectory();
        $extPath = $this->extendedPath ?? '';
        foreach (explode('/', $extPath) as $path) {
            $currentFullPath = concat_paths([$basePath, $path], false, false);
            if (!is_dir($currentFullPath)) {
                mkdir($currentFullPath);
            }
        }
        return str_replace('/', '\\', $extPath);
    }

    /**
     * Get directory of the generated class.
     *
     * @return string
     */
    public function getDirectory(): string
    {
        $type = $this->getType();
        return $this->basePaths[$type];
    }

    /**
     * Get type of the service class.
     *
     * @return string
     */
    public function getType(): string
    {
        return in_array($this->type, array_keys($this->basePaths)) ?
            $this->type : array_keys($this->basePaths)[0];
    }

    /**
     * Set type of the generated file.
     *
     * @param string $type
     * @return $this
     */
    public function setType(string $type = 'service'): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Generate the designated class.
     *
     * @return bool
     */
    public function generate(): bool
    {
        $fullDesignatedPath = $this->getFullDesignatedPath();

        $template = $this->getStubContent();

        $compact = $this->getStubCompact();
        $content = str_replace($compact['variables'], $compact['values'], $template);

        return file_put_contents($fullDesignatedPath, $content);
    }

    /**
     * Get full designated path.
     *
     * This will give the exact full path which not only
     * directory where the generated class will be deployed
     * but also the name of the file as well.
     *
     * @return string
     */
    public function getFullDesignatedPath(): string
    {
        $paths = [$this->getDirectory()];
        if ($extPath = $this->getExtendedPath()) {
            $paths[] = $extPath;
        }
        $paths[] = $this->getFileName();

        $fullPath = concat_paths($paths, false);
        // Check directory for file exists
        $destinedPath = str_replace($this->getFileName(), '', $fullPath);
        if (!file_exists($destinedPath)) {
            mkdir($destinedPath);
        }

        return $fullPath;
    }

    /**
     * Get file name of the generated class.
     *
     * @return string
     */
    public function getFileName(): string
    {
        return $this->getClassName() . '.php';
    }

    /**
     * Get stub content for generating class.
     *
     * @return string
     */
    public function getStubContent(): string
    {
        $path = $this->getStubPath();

        return file_get_contents($path);
    }

    /**
     * Get stub path of the class.
     *
     * @return string
     */
    public function getStubPath(): string
    {
        $type = $this->type;

        return $this->stubPaths[$type];
    }

    /**
     * Get stub variables and values for populating to template.
     *
     * @return array
     */
    public function getStubCompact(): array
    {
        $type = $this->getType();

        $compact = $this->stubCompacts[$type] ??
            $this->stubCompacts['service'];
        return [
            'variables' => array_keys($compact),
            'values' => array_values($compact),
        ];
    }
}
