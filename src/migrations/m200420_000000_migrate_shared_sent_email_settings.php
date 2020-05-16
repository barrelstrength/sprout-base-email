<?php /**
 * @link      https://sprout.barrelstrengthdesign.com
 * @copyright Copyright (c) Barrel Strength Design LLC
 * @license   https://craftcms.github.io/license
 */
/**
 * @link      https://sprout.barrelstrengthdesign.com
 * @copyright Copyright (c) Barrel Strength Design LLC
 * @license   https://craftcms.github.io/license
 */
/** @noinspection ClassConstantCanBeUsedInspection */

namespace barrelstrength\sproutbaseemail\migrations;

use barrelstrength\sproutbase\migrations\Install as SproutBaseInstall;
use barrelstrength\sproutbasesentemail\migrations\Install as SproutBaseSentEmailInstall;
use Craft;
use craft\db\Migration;
use craft\services\Plugins;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\base\NotSupportedException;
use yii\web\ServerErrorHttpException;

class m200420_000000_migrate_shared_sent_email_settings extends Migration
{
    /**
     * @return bool
     * @throws ErrorException
     * @throws Exception
     * @throws NotSupportedException
     * @throws ServerErrorHttpException
     */
    public function safeUp(): bool
    {
        // Make sure we have the sprout_settings table
        $migration = new SproutBaseInstall();
        ob_start();
        $migration->safeUp();
        ob_end_clean();

        $migration = new SproutBaseSentEmailInstall();
        ob_start();
        $migration->insertDefaultSettings();
        ob_end_clean();

        // Don't make the same config changes twice
        $projectConfig = Craft::$app->getProjectConfig();
        $pluginHandle = 'sprout-email';
        $schemaVersion = $projectConfig->get(Plugins::CONFIG_PLUGINS_KEY.'.'.$pluginHandle.'.schemaVersion', true);
        if (version_compare($schemaVersion, '4.3.0', '>=')) {
            return true;
        }

        $sproutEmailSettings = Craft::$app->getProjectConfig()->get(Plugins::CONFIG_PLUGINS_KEY.'.'.$pluginHandle.'.settings');

        if (!isset($sproutEmailSettings['enableSentEmails'])) {
            return true;
        }

        $newSentEmailSharedSettings = [
            'pluginNameOverride' => '',
            'enableSentEmails' => $sproutEmailSettings['enableSentEmails'] ?? false,
            'sentEmailsLimit' => $sproutEmailSettings['sentEmailsLimit'] ?? 5000,
            'cleanupProbability' => $sproutEmailSettings['cleanupProbability'] ?? 1000
        ];

        unset(
            $sproutEmailSettings['enableSentEmails'],
            $sproutEmailSettings['sentEmailsLimit'],
            $sproutEmailSettings['cleanupProbability']
        );

        $this->update('{{%sprout_settings}}', [
            'settings' => json_encode($newSentEmailSharedSettings),
        ], [
            'model' => 'barrelstrength\sproutbasesentemail\models\Settings',
        ]);

        Craft::$app->getProjectConfig()->set(Plugins::CONFIG_PLUGINS_KEY.'.'.$pluginHandle.'.settings', $sproutEmailSettings, 'Updated Sprout Email settings to remove Sent Email settings.');

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        echo "m200420_000000_migrate_shared_sent_email_settings cannot be reverted.\n";

        return false;
    }
}
