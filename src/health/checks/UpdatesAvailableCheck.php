<?php

namespace dwy\OhDear\health\checks;

use Craft;
use dwy\OhDear\health\Check as BaseCheck;

class UpdatesAvailableCheck extends BaseCheck
{
    public function run(): self
    {
        $count = Craft::$app->getUpdates()->getTotalAvailableUpdates();

        $this->shortSummary($count);

        if ($count > 0) {
            return $this->warning("{$count} updates available.");
        }

        return $this->ok();
    }
}
