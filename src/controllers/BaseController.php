<?php

namespace dwy\OhDear\controllers;

use Craft;
use craft\web\Controller;
use dwy\OhDear\Plugin;
use yii\web\ForbiddenHttpException;

abstract class BaseController extends Controller
{
    public function requireHealthCheckSecret()
    {
        $headers = Craft::$app->request->headers;
        $healthCheckSecret = Plugin::getInstance()->getSettings()->getHealthCheckSecret();

        if (empty($healthCheckSecret)) {
            throw new ForbiddenHttpException('Oh Dear Health Check Secret not configured.');
        }

        if ($headers->get('oh-dear-health-check-secret') !== $healthCheckSecret) {
            throw new ForbiddenHttpException('User is not permitted to perform this action.');
        }
    }
}
