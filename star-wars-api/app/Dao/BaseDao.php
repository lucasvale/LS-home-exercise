<?php

namespace App\Dao;

use Illuminate\Support\Facades\DB;
use PDO;

class BaseDao
{
    /** @var array bindings */
    protected array $bindings = [];

    /** @var array bindingsSubSelect */
    protected array $bindingsSubQuery = [];

    /** @var string  */
    protected string $query = '';

    /**
     * Parser all json values to array recursive
     *
     * @param array $values
     * @return array
     */
    protected function parserJsonToArrayRecursive(array $values): array
    {
        if (empty($values)) {
            return [];
        }

        array_walk_recursive($values, function (&$value, $key) {
            if (is_string($value)) {
                json_decode($value);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $value = json_decode($value, true);
                }
            }
        });

        return $values;
    }
    private function bindInSubQuery(): void
    {
        $this->query = str_replace(array_keys($this->bindingsSubQuery), array_values($this->bindingsSubQuery), $this->query);
    }

    /**
     * Execute query SQL
     *
     * @return array
     */
    protected function executeQuery(): array
    {
        $db = DB::connection()->getPdo();
        $db->setAttribute(\PDO::ATTR_EMULATE_PREPARES, true);

        if (!empty($this->bindingsSubQuery)) {
            $this->bindInSubQuery();
        }

        $stmt = $db->prepare($this->query);
        if (!empty($this->bindings)) {
            foreach ($this->bindings as $key => $value) {
                $pdoParam = match (gettype($value)) {
                    'string'  => PDO::PARAM_STR,
                    'integer' => PDO::PARAM_INT,
                    'boolean' => PDO::PARAM_BOOL,
                    'NULL'    => PDO::PARAM_NULL,
                    'double'  => PDO::PARAM_STR,
                };

                $stmt->bindValue($key, $value, $pdoParam);
            }
        }

        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $this->parserJsonToArrayRecursive($result);
    }
}

