<?php
/**
 * @link      https://sprout.barrelstrengthdesign.com/
 * @copyright Copyright (c) Barrel Strength Design LLC
 * @license   http://sprout.barrelstrengthdesign.com/license
 */

namespace barrelstrength\sproutbaseemail\migrations;

use barrelstrength\sproutbaseemail\records\NotificationEmail as NotificationEmailRecord;
use craft\db\Migration;
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
    }

    public function safeDown()
    {
        $this->dropTableIfExists(NotificationEmailRecord::tableName());
    }
}