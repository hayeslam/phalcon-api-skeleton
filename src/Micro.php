<?php

namespace App;

use App\Exceptions\HttpException;

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
        $this->attachMiddlewares();
        $this->loadRoutes();
        $this->notFound(function () {
            throw HttpException::notFound();
        });
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

    public function attachMiddlewares()
    {
        $middlewares = $this->getMiddlewares();
        foreach ($middlewares as $class => $function) {
            $this->{$function}(new $class());
        }
    }
}
