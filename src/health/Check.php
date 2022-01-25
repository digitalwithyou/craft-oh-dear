<?php

namespace dwy\OhDear\health;

use OhDear\HealthCheckResults\CheckResult;

abstract class Check extends CheckResult
{
    public array $meta = [];
    public string $name = '';
    public string $label = '';

    /**
     * @param string $name
     * @param string $notificationMessage
     * @param string $shortSummary
     * @param string $status
     * @param array<int, mixed> $meta
     */
    public function __construct() {
        parent::__construct(
            $this->getName(),
            $this->getLabel()
        );
    }

    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function label(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getLabel(): string
    {
        if ($this->label) {
            return $this->label;
        }

        $name = $this->getName();
        $name = snake_case($name); // see src/helpers.php
        $name = str_replace('_', ' ', $name);
        $name = title_case($name); // see src/helpers.php

        return $name;
    }

    public function getName(): string
    {
        if ($this->name) {
            return $this->name;
        }

        $baseName = class_basename(static::class); // see src/helpers.php

        return before_last($baseName, 'Check'); // see src/helpers.php
    }

    /**
     * @param array<int, mixed> $meta
     *
     * @return $this
     */
    public function meta(array $meta): self
    {
        $this->meta = $meta;

        return $this;
    }

    public function ok(string $message = ''): self
    {
        $this->notificationMessage = $message;

        $this->status = self::STATUS_OK;

        return $this;
    }

    public function warning(string $message = ''): self
    {
        $this->notificationMessage = $message;

        $this->status = self::STATUS_WARNING;

        return $this;
    }

    public function failed(string $message = ''): self
    {
        $this->notificationMessage = $message;

        $this->status = self::STATUS_FAILED;

        return $this;
    }

    abstract public function run(): self;
}
