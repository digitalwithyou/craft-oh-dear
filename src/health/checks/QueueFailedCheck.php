<?php

namespace dwy\OhDear\health\checks;

use Craft;
use dwy\OhDear\health\Check as BaseCheck;

class QueueFailedCheck extends BaseCheck
{
    public function run(): self
    {
        $failedJobs = Craft::$app->get('queue')->getTotalFailed();

        $this->shortSummary($failedJobs);

        if ($failedJobs > 0) {
            return $this->failed("Has {$failed} failed jobs.");
        }

        return $this->ok();
    }
}
