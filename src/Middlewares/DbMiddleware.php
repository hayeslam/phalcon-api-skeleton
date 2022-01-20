<?php

namespace App\Middlewares;

use App\Injectable;
use Phalcon\Db\Adapter\AdapterInterface as DbAdapterInterface;
use Phalcon\Events\Event;
use Phalcon\Mvc\Micro\MiddlewareInterface;

class DbMiddleware extends Injectable implements MiddlewareInterface
{
    /**
     * @param Event $event
     * @param DbAdapterInterface $connection
     * @return bool
     */
    public function beforeQuery(Event $event, DbAdapterInterface $connection): bool
    {
        $profiler = $this->dbProfiler;
        $profiler->startProfile(
            $connection->getSQLStatement(),
            $connection->getSQLVariables(),
            $connection->getSQLBindTypes()
        );

        return true;
    }

    public function afterQuery(): bool
    {
        $profiler = $this->dbProfiler;
        $profile = $profiler->getLastProfile();
        $this->logger->debug(
            sprintf(
                '%s - [%s] - et:%f',
                $profile->getSQLStatement(),
                json_encode($profile->getSQLVariables()),
                $profiler->getTotalElapsedSeconds()
            )
        );

        $profiler->stopProfile();

        return true;
    }

    /**
     * @param \Phalcon\Mvc\Micro $application
     * @return bool
     */
    public function call(\Phalcon\Mvc\Micro $application): bool
    {
        return true;
    }
}
