<?php

namespace dwy\OhDear\models;

use Craft;
use craft\base\Model;
use craft\behaviors\EnvAttributeParserBehavior;

class Settings extends Model
{
    /**
     * @var string
     */
    public $apiKey;

    /**
     * @var string
     */
    public $healthCheckSecret;

    public function behaviors()
    {
        return [
            'parser' => [
                'class' => EnvAttributeParserBehavior::class,
                'attributes' => ['apiKey', 'healthCheckSecret'],
            ],
        ];
    }

    public function rules()
    {
        return [
            [['healthCheckSecret'], 'required'],
        ];
    }

    public function getApiKey(): ?string
    {
        return Craft::parseEnv($this->apiKey);
    }

    public function getHealthCheckSecret(): ?string
    {
        return Craft::parseEnv($this->healthCheckSecret);
    }
}
