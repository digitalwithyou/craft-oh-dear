<?php

namespace dwy\OhDear;

use Craft;
use craft\base\Plugin as BasePlugin;
use craft\events\RegisterUrlRulesEvent;
use craft\web\Application;
use craft\web\UrlManager;
use dwy\OhDear\models\Settings;
use yii\base\Event;

class Plugin extends BasePlugin
{
    public $hasCpSettings = true;

    public function init()
    {
        Event::on(Application::class, Application::EVENT_INIT, function() {
            parent::init();

            if (Craft::$app->getRequest()->getIsConsoleRequest()) {
                return;
            }

            $this->_registerSiteRoutes();
        });
    }

    protected function createSettingsModel()
    {
        return new Settings();
    }

    protected function settingsHtml()
    {
        return Craft::$app->getView()->renderTemplate(
            'oh-dear/settings',
            [ 'settings' => $this->getSettings() ]
        );
    }

    private function _registerSiteRoutes()
    {
        Event::on(UrlManager::class, UrlManager::EVENT_REGISTER_SITE_URL_RULES, function(RegisterUrlRulesEvent $event) {
            $event->rules['oh-dear-health-check-results'] = 'oh-dear/health/check-results';
        });
    }
}
