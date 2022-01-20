<?php

namespace App;

use Monolog\Logger;
use Phalcon\Db\Profiler;

/**
 * @property Logger $logger
 * @property Profiler $dbProfiler
 */
class Injectable extends \Phalcon\Di\Injectable
{
}
