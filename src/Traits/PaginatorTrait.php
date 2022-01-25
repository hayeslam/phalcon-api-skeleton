<?php

namespace App\Traits;

use Phalcon\Mvc\Model\Query\BuilderInterface;
use Phalcon\Mvc\ModelInterface;
use Phalcon\Paginator\Adapter\AdapterInterface;
use Phalcon\Paginator\PaginatorFactory;
use Phalcon\Paginator\RepositoryInterface;

trait PaginatorTrait
{
    /**
     * @param $source
     * @param array $parameters
     * @param int $page
     * @param int $pageSize
     * @return AdapterInterface
     */
    public function paginator($source, array $parameters, int $page, int $pageSize): AdapterInterface
    {
        $adapter = '';
        if ($source instanceof ModelInterface) {
            $adapter = 'model';
            $options['model'] = get_class($source);
            $options['parameters'] = $parameters;
        } elseif ($source instanceof BuilderInterface) {
            $adapter = 'queryBuilder';
            $options['builder'] = $source;
            $options['columns'] = $parameters;
        } elseif (is_array($source)) {
            $adapter = 'nativeArray';
            $options['data'] = $source;
        }
        $options['page'] = $page;
        $options['limit'] = $pageSize;

        return (new PaginatorFactory())->newInstance($adapter, $options);
    }

    /**
     * @param $variable
     * @param int $page
     * @param int $pageSize
     * @param callable|null $formatter
     * @return array
     */
    public function paginate($variable, int $page = 1, int $pageSize = 10, ?callable $formatter = null): array
    {
        $paginator = $this->paginator($variable, [], $page, $pageSize);
        return $this->paginateFormat($paginator->paginate(), $formatter);
    }

    /**
     * @param RepositoryInterface $paginate
     * @param callable|null $formatter
     * @return array
     */
    private function paginateFormat(RepositoryInterface $paginate, ?callable $formatter = null): array
    {
        if (is_callable($formatter)) {
            $result = call_user_func($formatter, $paginate);
        } else {
            $result = [
                'items' => $paginate->getItems(),
                'count' => $paginate->getTotalItems(),
            ];
        }

        return $result;
    }
}
