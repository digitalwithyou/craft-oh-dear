<?php

namespace dwy\OhDear\health\checks;

use Craft;
use craft\db\Connection;
use craft\helpers\Db;
use dwy\OhDear\health\Check as BaseCheck;
use RequirementsChecker;

class RequirementsCheck extends BaseCheck
{
    public function run(): self
    {
        $results = $this->runRequirementsChecker();

        if ($results['summary']['errors'] > 0) {
            $this->shortSummary($results['summary']['errors']);

            return $this->failed($this->getError($results));
        }

        if ($results['summary']['warnings'] > 0) {
            $this->shortSummary($results['summary']['warnings']);

            return $this->warning($this->getWarning($results));
        }

        $this->shortSummary(0);

        return $this->ok();
    }

    private function runRequirementsChecker(): array
    {
        $requirementsChecker = new RequirementsChecker();

        $databaseConfig = Craft::$app->getConfig()->getDb();
        $requirementsChecker->dsn = $databaseConfig->dsn;
        $requirementsChecker->dbDriver = $databaseConfig->dsn ? Db::parseDsn($databaseConfig->dsn, 'driver') : Connection::DRIVER_MYSQL;
        $requirementsChecker->dbUser = $databaseConfig->user;
        $requirementsChecker->dbPassword = $databaseConfig->password;
        $requirementsChecker->checkCraft();

        return $requirementsChecker->getResult();
    }

    private function getError($results): ?string
    {
        return $this->getMemo($results['requirements'], 'error');
    }

    private function getWarning($results): ?string
    {
        return $this->getMemo($results['requirements'], 'warning');
    }

    /**
     * @var array $requirements
     * @var string $type  'error' or 'warning'
     */
    private function getMemo(array $requirements, string $type): ?string
    {
        $results = array_filter($requirements, function($requirement) use ($type) {
            return $requirement[$type] == true;
        });

        if (count($results) > 0) {
            $memo = reset($results)['memo'];

            return strip_tags($memo);
        }

        return null;
    }
}
