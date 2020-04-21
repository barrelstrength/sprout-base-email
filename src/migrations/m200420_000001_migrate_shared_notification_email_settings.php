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
            'pluginNameOverride' => $sproutEmailSettings['pluginNameOverride'],
            'enableNotificationEmails' => $sproutEmailSettings['enableNotificationEmails'],
            'emailTemplateId' => $sproutEmailSettings['emailTemplateId'],
            'enablePerEmailEmailTemplateIdOverride' => $sproutEmailSettings['enablePerEmailEmailTemplateIdOverride']
        ];

        $this->insert('{{%sprout_settings}}', [
            'model' => 'barrelstrength\sproutbaseemail\models\Settings',
            'settings' => json_encode($newEmailSharedSettings)
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
