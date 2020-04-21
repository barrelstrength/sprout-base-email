<?php
/**
 * @link      https://sprout.barrelstrengthdesign.com/
 * @copyright Copyright (c) Barrel Strength Design LLC
 * @license   http://sprout.barrelstrengthdesign.com/license
 */

namespace barrelstrength\sproutbaseemail\migrations;

use barrelstrength\sproutbase\records\Settings as SproutBaseSettingsRecord;
use barrelstrength\sproutbaseemail\models\Settings as SproutBaseEmailSettings;
use barrelstrength\sproutbaseemail\records\NotificationEmail as NotificationEmailRecord;
use craft\db\Migration;
use craft\db\Query;
use craft\db\Table;

class Install extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $notificationTableName = NotificationEmailRecord::tableName();

        if ($this->getDb()->tableExists($notificationTableName)) {
            $this->createTable($notificationTableName,
                [
                    'id' => $this->primaryKey(),
                    'viewContext' => $this->string(),
                    'titleFormat' => $this->string(),
                    'emailTemplateId' => $this->string(),
                    'eventId' => $this->string(),
                    'settings' => $this->text(),
                    'sendRule' => $this->text(),
                    'subjectLine' => $this->string()->notNull(),
                    'defaultBody' => $this->text(),
                    'recipients' => $this->text(),
                    'cc' => $this->text(),
                    'bcc' => $this->text(),
                    'listSettings' => $this->text(),
                    'fromName' => $this->string(),
                    'fromEmail' => $this->string(),
                    'replyToEmail' => $this->string(),
                    'sendMethod' => $this->string(),
                    'enableFileAttachments' => $this->boolean(),
                    'dateCreated' => $this->dateTime(),
                    'dateUpdated' => $this->dateTime(),
                    'fieldLayoutId' => $this->integer(),
                    'uid' => $this->uid()
                ]
            );

            $this->addForeignKey(null, $notificationTableName, ['id'], Table::ELEMENTS, ['id'], 'CASCADE');
        }

        $this->insertDefaultSettings();
    }

    public function safeDown()
    {
        $this->dropTableIfExists(NotificationEmailRecord::tableName());
    }

    public function insertDefaultSettings()
    {
        $settingsRow = (new Query())
            ->select(['*'])
            ->from([SproutBaseSettingsRecord::tableName()])
            ->where(['model' => SproutBaseEmailSettings::class])
            ->one();

        if ($settingsRow === null) {

            $settings = new SproutBaseEmailSettings();

            $settingsArray = [
                'model' => SproutBaseEmailSettings::class,
                'settings' => json_encode($settings->toArray())
            ];

            $this->insert(SproutBaseSettingsRecord::tableName(), $settingsArray);
        }
    }
}