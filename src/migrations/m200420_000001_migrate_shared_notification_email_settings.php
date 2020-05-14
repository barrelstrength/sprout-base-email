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
use barrelstrength\sproutbaseemail\migrations\Install as SproutBaseEmailInstall;
use Craft;
use craft\db\Migration;
use craft\services\Plugins;

class m200420_000001_migrate_shared_notification_email_settings extends Migration
{
    /**
     * @return bool
     */
    public function safeUp(): bool
    {

        // Make sure we have the sprout_settings table
        $migration = new SproutBaseInstall();
        ob_start();
        $migration->safeUp();
        ob_end_clean();

        $migration = new SproutBaseEmailInstall();
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

        if (!$sproutEmailSettings) {
            return true;
        }

        $newEmailSharedSettings = [
            'pluginNameOverride' => $sproutEmailSettings['pluginNameOverride'] ?? '',
            'enableNotificationEmails' => $sproutEmailSettings['enableNotificationEmails'] ?? 1,
            'emailTemplateId' => $sproutEmailSettings['emailTemplateId'] ?? 'barrelstrength\sproutbaseemail\emailtemplates\BasicTemplates',
            'enablePerEmailEmailTemplateIdOverride' => $sproutEmailSettings['enablePerEmailEmailTemplateIdOverride'] ?? 0
        ];

        $this->update('{{%sprout_settings}}', [
            'settings' => json_encode($newEmailSharedSettings)
        ], [
            'model' => 'barrelstrength\sproutbaseemail\models\Settings'
        ]);

        Craft::$app->getProjectConfig()->remove(Plugins::CONFIG_PLUGINS_KEY.'.'.$pluginHandle.'.settings', 'Migrated Sprout Email settings to Sent Base Email.');

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        echo "m200420_000001_migrate_shared_notification_email_settings cannot be reverted.\n";

        return false;
    }
}
