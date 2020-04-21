<?php /**
 * @link https://sprout.barrelstrengthdesign.com
 * @copyright Copyright (c) Barrel Strength Design LLC
 * @license https://craftcms.github.io/license
 */ /**
 * @link https://sprout.barrelstrengthdesign.com
 * @copyright Copyright (c) Barrel Strength Design LLC
 * @license https://craftcms.github.io/license
 */ /** @noinspection ClassConstantCanBeUsedInspection */

namespace barrelstrength\sproutbaseemail\migrations;

use barrelstrength\sproutbaseemail\migrations\m200219_000000_clean_up_cc_bcc_emailList_fields;
use craft\db\Migration;
use craft\services\Plugins;
use Craft;
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

        $this->insert('{{%sprout_settings}}', [
            'model' => 'barrelstrength\sproutbasesentemail\models\Settings',
            'settings' => json_encode($newSentEmailSharedSettings)
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
