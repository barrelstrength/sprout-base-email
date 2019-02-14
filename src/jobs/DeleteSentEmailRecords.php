<?php
/**
 * @link      https://sprout.barrelstrengthdesign.com/
 * @copyright Copyright (c) Barrel Strength Design LLC
 * @license   http://sprout.barrelstrengthdesign.com/license
 */

namespace barrelstrength\sproutbaseemail\jobs;

use barrelstrength\sproutbase\SproutBase;
use barrelstrength\sproutemail\records\SentEmail;
use craft\queue\BaseJob;
use Craft;
use craft\db\Query;

/**
 * Delete404 job
 */
class DeleteSentEmailRecords extends BaseJob
{
    /**
     * @var int
     */
    public $siteId;

    /**
     * @var int
     */
    public $limit;

    /**
     * Returns the default description for this job.
     *
     * @return string
     */
    protected function defaultDescription(): string
    {
        return Craft::t('sprout-base', 'Cleaning up Sent Email Records');
    }

    /**
     * @param \craft\queue\QueueInterface|\yii\queue\Queue $queue
     *
     * @return bool
     * @throws \Throwable
     */
    public function execute($queue): bool
    {
        /** @var SentEmail[] $sentEmails */
        $sentEmails = (new Query())
            ->select(['sentemail.*'])
            ->from(['{{%sproutemail_sentemail}} sentemail'])
            ->limit(null)
            ->leftJoin('{{%elements}} el', '[[sentemail.id]] = [[el.id]]')
            ->where([
                'el.id' => null
            ])->all();

        $totalSteps = count($sentEmails);

        if (empty($sentEmails)) {
            return true;
        }

        foreach ($sentEmails as $key => $sentEmail) {
            $step = $key + 1;
            $this->setProgress($queue, $step / $totalSteps);

            $sentEmailId = $sentEmail['id'];

            $response = (new Query)
                ->createCommand()
                ->delete('{{%sproutemail_sentemail}}', ['id' =>$sentEmailId])
                ->execute();

            if (!$response) {
                SproutBase::error('Unable to delete Sent Email ID: '.$sentEmail->id);
            }
        }

        return true;
    }
}