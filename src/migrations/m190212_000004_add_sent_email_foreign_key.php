<?php /** @noinspection ClassConstantCanBeUsedInspection */

namespace barrelstrength\sproutbaseemail\migrations;

use craft\db\Migration;
use Craft;

class m190212_000004_add_sent_email_foreign_key extends Migration
{
    private $sentEmailTable = '{{%sproutemail_sentemail}}';

    /**
     * @inheritdoc
     */
    public function safeUp(): bool
    {
        $sentTable = $this->getDb()->tableExists($this->sentEmailTable);

        if ($sentTable) {
            // Updates foreign key if it does not exist. Try catch avoid errors if it exist
            try {
                $this->addForeignKey(null, $this->sentEmailTable,
                    ['id'], '{{%elements}}', ['id'], 'CASCADE');
            } catch (\Exception $e) {
                Craft::info('Foreign Key already exists', __METHOD__);
            }
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        echo "m190212_000004_add_sent_email_foreign_key cannot be reverted.\n";
        return false;
    }
}
