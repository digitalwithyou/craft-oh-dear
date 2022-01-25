<?php

namespace dwy\OhDear\controllers;

use Craft;
use craft\web\Controller;
use dwy\OhDear\Plugin;
use dwy\OhDear\health\checks\DatabaseCheck;
use dwy\OhDear\health\checks\DebugModeCheck;
use dwy\OhDear\health\checks\DeprecationWarningsCheck;
use dwy\OhDear\health\checks\EnvironmentCheck;
use dwy\OhDear\health\checks\QueueFailedCheck;
use dwy\OhDear\health\checks\RequirementsCheck;
use dwy\OhDear\health\checks\UpdatesAvailableCheck;
use dwy\OhDear\health\checks\UsedDiskSpaceCheck;
use OhDear\HealthCheckResults\CheckResults;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

class HealthController extends BaseController
{
    protected $allowAnonymous = self::ALLOW_ANONYMOUS_LIVE;

    protected $checks = [
        DatabaseCheck::class,
        DebugModeCheck::class,
        DeprecationWarningsCheck::class,
        EnvironmentCheck::class,
        QueueFailedCheck::class,
        RequirementsCheck::class,
        UpdatesAvailableCheck::class,
        UsedDiskSpaceCheck::class,
    ];

    public function actionCheckResults(): Response
    {
        $this->requireHealthCheckSecret();

        $checkResults = $this->getCheckResults();

        $responseData = json_decode($checkResults->toJson());

        $this->response->setNoCacheHeaders();

        return $this->asJson($responseData);
    }

    private function getCheckResults(): CheckResults
    {
        $checkResults = new CheckResults();

        foreach ($this->checks as $checkClass) {
            $check = new $checkClass();

            $checkResults->addCheckResult($check->run());
        }

        return $checkResults;
    }
}
