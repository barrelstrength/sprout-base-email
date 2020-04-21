<?php
/**
 * @link      https://sprout.barrelstrengthdesign.com/
 * @copyright Copyright (c) Barrel Strength Design LLC
 * @license   http://sprout.barrelstrengthdesign.com/license
 */

namespace barrelstrength\sproutbaseemail\services;

use barrelstrength\sproutbase\SproutBase;
use barrelstrength\sproutbaseemail\models\Settings as SproutBaseEmailSettings;
use craft\base\Model;
use yii\base\Component;

/**
 *
 * @property null|Model              $pluginSettings
 * @property SproutBaseEmailSettings $emailSettings
 * @property int                     $descriptionLength
 */
class Settings extends Component
{
    /**
     * @return SproutBaseEmailSettings
     */
    public function getEmailSettings(): SproutBaseEmailSettings
    {
        /** @var SproutBaseEmailSettings $settings */
        $settings = SproutBase::$app->settings->getBaseSettings(SproutBaseEmailSettings::class, 'sprout-email');

        return $settings;
    }
}
