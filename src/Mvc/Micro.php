<?php

namespace App\Mvc;

use App\Middlewares\MicroMiddleware;

use const ROOT_PATH;

class Micro extends \Phalcon\Mvc\Micro
{
    public function __construct(\Phalcon\Di\DiInterface $container = null)
    {
        parent::__construct($container);

        $this->setup();
    }

    protected function setup()
    {
        $this->setEventsManager($this->di->getShared('eventsManager'));
        $this->getEventsManager()->attach('micro', new MicroMiddleware());
        $this->attachMiddlewares();
        $this->loadRoutes();
    }

    protected function loadRoutes(): Micro
    {
        $app = $this;
        require_once ROOT_PATH . '/config/routes.php';

        return $this;
    }

    protected function getMiddlewares(): array
    {
        return [
        ];
    }

    protected function attachMiddlewares()
    {
        $middlewares = $this->getMiddlewares();
        foreach ($middlewares as $class => $function) {
            $this->{$function}(new $class());
        }
    }
}
