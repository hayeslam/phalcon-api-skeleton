<?php

namespace App\Mvc;

use App\Traits\DbTrait;
use App\Traits\PaginatorTrait;
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
}
