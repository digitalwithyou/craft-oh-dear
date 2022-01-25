<?php

namespace dwy\OhDear\health\checks;

use Craft;
use dwy\OhDear\health\Check as BaseCheck;

class EnvironmentCheck extends BaseCheck
{
    protected string $expectedEnvironment = 'production';

    public function expectEnvironment(string $expectedEnvironment): self
    {
        $this->expectedEnvironment = $expectedEnvironment;

        return $this;
    }

    public function run(): self
    {
        $actualEnvironment = Craft::$app->getConfig()->env;

        $this->meta([
                 'actual' => $actualEnvironment,
                 'expected' => $this->expectedEnvironment,
             ])
             ->shortSummary($actualEnvironment);

        if ($this->expectedEnvironment !== $actualEnvironment) {
            return $this->failed("The environment was expected to be `{$this->expectedEnvironment}`, but actually was `{$actualEnvironment}`");
        }

        return $this->ok();
    }
}
