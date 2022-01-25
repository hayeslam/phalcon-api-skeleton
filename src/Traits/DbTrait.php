<?php

namespace App\Traits;

use Phalcon\Db\Adapter\AdapterInterface;

trait DbTrait
{
    abstract protected function _getConnection(): AdapterInterface;

    /**
     * @param callable $callable
     * @param AdapterInterface|null $connection
     * @return bool|mixed
     * @throws \Exception
     */
    protected function doTransaction(callable $callable, ?AdapterInterface $connection = null)
    {
        if ($connection === null) {
            $connection = $this->_getConnection();
        }
        $result = false;
        $exception = null;
        try {
            $connection->begin();
            $result = call_user_func($callable);
        } catch (\Exception $e) {
            $exception = $e;
        }
        if ($result !== true) {
            $connection->rollback();
        } else {
            $connection->commit();
        }
        if ($exception) {
            throw $exception;
        }

        return $result;
    }

    public function batchInsert(string $table, $row)
    {
        // todo batch insert
    }
}
