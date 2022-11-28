<?php

namespace App\Traits;

use Illuminate\Support\Facades\Request;

trait RequestHasRelations
{
    /**
     * Alias for getRelations
     *
     * @return array
     */
    public function relations(): array
    {
        return $this->getRelations();
    }

    /**
     * Get relation from request
     *
     * @return array
     */
    public function getRelations(): array
    {
        if (!$this->relationNames) {
            return [];
        }

        $relations = [];
        foreach ($this->parseQueryString(Request::server('QUERY_STRING')) as $key => $value) {
            if (array_key_exists($key, $this->relationNames)) {
                $relationName = str_replace('with_', '', $key);
                $relationName = str_camel_case($relationName);
                if (strtobool($this->input($key, $value))) {
                    $relations[] = $relationName;
                }
            }
        }

        return $relations;
    }

    /**
     * Parse query string
     *
     * @param $data
     * @return array
     */
    public function parseQueryString($data): array
    {
        $data = rawurldecode($data);
        $pattern = '/(?:^|(?<=&))[^=&\[]*[^=&\[]*/';
        $data = preg_replace_callback($pattern, function ($match) {
            return bin2hex(urldecode($match[0]));
        }, $data);
        parse_str($data, $values);

        return array_combine(array_map('hex2bin', array_keys($values)), $values);
    }

    /**
     * Alias for getRelationCount
     *
     * @return array
     */
    public function relationCounts(): array
    {
        return $this->getRelationCounts();
    }

    /**
     * Get relation count
     *
     * @return array
     */
    public function getRelationCounts(): array
    {
        if (!$this->relationCountNames) {
            return [];
        }
        $relationCounts = [];
        foreach ($this->relationCountNames as $name => $defaultValue) {
            $relationCountName = str_replace('with_', '', $name);
            $relationCountName = str_replace('_count', '', $relationCountName);
            if ($this->input($name, $defaultValue)) {
                $relationCounts[] = $relationCountName;
            }
        }

        return $relationCounts;
    }

    /**
     * Prepare the relation from supplied input
     *
     * @return void
     */
    protected function prepareRelationInputs(): void
    {
        if (!$this->relationNames) {
            return;
        }
        foreach ($this->relationNames as $requestKey => $defaultValue) {
            if ($this->has($requestKey)) {
                $inputValue = strtobool($this->input($requestKey));
                $this->merge([$requestKey => $inputValue]);
            }
        }
    }
}
