<?php

namespace App\Mvc;

use App\Injectable;
use App\Traits\DbTrait;
use App\Traits\PaginatorTrait;
use Phalcon\Db\Adapter\AdapterInterface;
use Phalcon\Di\DiInterface;
use Phalcon\Mvc\Model\ResultsetInterface;
use Phalcon\Mvc\ModelInterface;

abstract class Model extends \Phalcon\Mvc\Model
{
    use DbTrait;
    use PaginatorTrait;

    public function initialize()
    {
        // todo initialize model
    }

    /**
     * @return static
     */
    public static function instance(): Model
    {
        return new static();
    }

    /**
     * @return DiInterface|Injectable
     */
    public function getDI(): DiInterface
    {
        return parent::getDI();
    }

    /**
     * @param $parameters
     * @return ModelInterface|null|static
     */
    public static function findFirst($parameters = null): ?ModelInterface
    {
       return parent::findFirst($parameters);
    }

    /**
     * @param $parameters
     * @return ResultsetInterface|static[]
     */
    public static function find($parameters = null): ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * @param array|null $variable
     * @param int $page
     * @param int $pageSize
     * @param callable|null $formatter
     * @return array
     */
    public function paginate(?array $variable = null, int $page = 1, int $pageSize = 10, ?callable $formatter = null
    ): array {
        $parameters = $variable ?: [];
        $paginator = $this->paginator($this, $parameters, $page, $pageSize);

        return $this->paginateFormat($paginator->paginate(), $formatter);
    }

    /**
     * @param array $rows
     * @param int $length
     * @param AdapterInterface|null $connection
     * @return bool
     * @throws \Throwable
     */
    public function batchInsert(array $rows, int $length = 200, AdapterInterface $connection = null): bool
    {
        if (empty($rows)) {
            return false;
        }
        $data = array_chunk($rows, $length);
        $closure = function ($instance, $conn) use($data) {
            $result = false;
            $columns = array_keys($data[0][0] ?? []);
            array_walk($columns, function (&$column) {
                $column =  "`$column`";
            });
            $sql = sprintf("INSERT INTO %s (%s)", $instance->getSource(), implode(',', $columns));
            foreach ($data as $datum) {
                $values = [];
                foreach ($datum as $item) {
                    array_walk($item, function (&$value) use ($conn) {
                        $value =  $conn->escapeString($value);
                    });
                    $values[] = '(' . implode(',', $item) . ')';
                }
                $sql .= " VALUE ";
                $sql .= implode(',', $values);
                if (!$result = $conn->execute($sql)) {
                    break;
                }
            }

            return $result;
        };

        return $this->doTransaction($closure, $connection);
    }
}
