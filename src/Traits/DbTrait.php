<?php

namespace App\Traits;

use Phalcon\Db\Adapter\AdapterInterface;

trait DbTrait
{
    /**
     * @param \Closure $callable
     * @param AdapterInterface|null $connection
     * @param bool $nesting
     * @return bool
     * @throws \Throwable
     */
    protected function doTransaction(
        \Closure $callable,
        AdapterInterface $connection = null,
        bool $nesting = true
    ): bool {
        if ($connection === null) {
            $connection = $this->getDI()->get('db');
        }
        $result = false;
        $exception = null;
        $connection->begin($nesting);
        try {
            $result = $callable($this, $connection);
        } catch (\Throwable $e) {
            $exception = $e;
        }
        if ($result !== true) {
            $connection->rollback($nesting);
        } else {
            $connection->commit($nesting);
        }
        if ($exception) {
            throw $exception;
        }

        return $result;
    }
}
