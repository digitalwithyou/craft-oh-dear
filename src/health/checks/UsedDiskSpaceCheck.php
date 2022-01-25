<?php

namespace dwy\OhDear\health\checks;

use Craft;
use dwy\OhDear\health\Check as BaseCheck;

class UsedDiskSpaceCheck extends BaseCheck
{
    protected int $warningThreshold = 70;
    protected int $errorThreshold = 90;

    public function warnWhenUsedSpaceIsAbovePercentage(int $percentage): self
    {
        $this->warningThreshold = $percentage;

        return $this;
    }

    public function failWhenUsedSpaceIsAbovePercentage(int $percentage): self
    {
        $this->errorThreshold = $percentage;

        return $this;
    }

    public function run(): self
    {
        $diskSpaceUsedPercentage = $this->getDiskUsagePercentage();

        $this->meta(['disk_space_used_percentage' => $diskSpaceUsedPercentage])
             ->shortSummary($diskSpaceUsedPercentage . '%');

        if ($diskSpaceUsedPercentage > $this->errorThreshold) {
            return $this->failed("The disk is almost full ({$diskSpaceUsedPercentage}% used).");
        }

        if ($diskSpaceUsedPercentage > $this->warningThreshold) {
            return $this->warning("The disk is almost full ({$diskSpaceUsedPercentage}% used).");
        }

        return $this->ok();
    }

    protected function getDiskUsagePercentage(): int
    {
        $directory = Craft::$app->getBasePath();

        $totalSpace = disk_total_space($directory);
        $freeSpace = disk_free_space($directory);
        $usedSpace = $totalSpace - $freeSpace;
        $percentage = ($usedSpace/$totalSpace) * 100;

        return (int) $percentage;
    }
}
