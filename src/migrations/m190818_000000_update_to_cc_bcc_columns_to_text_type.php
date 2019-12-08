<?php

namespace barrelstrength\sproutbaseemail\migrations;

use craft\db\Migration;
use yii\base\NotSupportedException;

/**
 * m190818_000000_update_to_cc_bcc_columns_to_text_type migration.
 */
class m190818_000000_update_to_cc_bcc_columns_to_text_type extends Migration
{
    /**
     * @return bool
     * @throws NotSupportedException
     */
    public function safeUp(): bool
    {
        $table = '{{%sproutemail_notificationemails}}';

        if ($this->db->columnExists($table, 'recipients')) {
            $this->alterColumn($table, 'recipients', $this->text());
        }

        if ($this->db->columnExists($table, 'cc')) {
            $this->alterColumn($table, 'cc', $this->text());
        }

        if ($this->db->columnExists($table, 'bcc')) {
            $this->alterColumn($table, 'bcc', $this->text());
        }

        // Our install migration created this as a string for a short time
        // so there is a change it needs to be updated
        if ($this->db->columnExists($table, 'sendRule')) {
            $this->alterColumn($table, 'sendRule', $this->text());
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        echo "m190818_000000_update_to_cc_bcc_columns_to_text_type cannot be reverted.\n";
        return false;
    }
}
