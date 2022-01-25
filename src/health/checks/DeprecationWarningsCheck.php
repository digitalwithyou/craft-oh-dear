<?php

namespace dwy\OhDear\health\checks;

use Craft;
use dwy\OhDear\health\Check as BaseCheck;

class DeprecationWarningsCheck extends BaseCheck
{
    public function run(): self
    {
        $count = Craft::$app->getDeprecator()->getTotalLogs();

        $this->shortSummary($count);

        if ($count > 0) {
            return $this->warning("{$count} deprecation warnings.");
        }

        return $this->ok();
    }
}
