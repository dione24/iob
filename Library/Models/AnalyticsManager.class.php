<?php

namespace Library\Models;

use \Library\Entities\Analytics;

abstract class AnalyticsManager extends \Library\Manager
{
    abstract protected function GetOperations($debut, $fin);
}