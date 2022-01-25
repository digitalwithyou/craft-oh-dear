<?php

namespace dwy\OhDear\health\checks;

use Craft;
use craft\db\Connection;
use dwy\OhDear\health\Check as BaseCheck;

class DatabaseCheck extends BaseCheck
{
    public function run(): self
    {
        try {
            $db = Craft::$app->getDb();

            $this->meta([
                'driver' => $db->getDriverName(),
            ]);

            $command = $db->createCommand('SELECT COUNT(id) FROM `sites`');
            $command->queryAll();

            return $this->ok();
        } catch (Exception $exception) {
            return $this->failed("Could not connect to the database: `{$exception->getMessage()}`");
        }
    }
}
