<?php

namespace barrelstrength\sproutbaseemail\elements\db;

use craft\elements\db\ElementQuery;
use craft\base\Element;
use Craft;

class NotificationEmailQuery extends ElementQuery
{
    /**
     * @var string
     */
    public $pluginHandle;

    /**
     * @inheritdoc
     */
    protected function beforePrepare(): bool
    {
        $this->joinElementTable('sproutemail_notificationemails');
        $this->query->select([
            'sproutemail_notificationemails.pluginHandle',
            'sproutemail_notificationemails.titleFormat',
            'sproutemail_notificationemails.emailTemplateId',
            'sproutemail_notificationemails.eventId',
            'sproutemail_notificationemails.settings',
            'sproutemail_notificationemails.subjectLine',
            'sproutemail_notificationemails.defaultBody',
            'sproutemail_notificationemails.recipients',
            'sproutemail_notificationemails.cc',
            'sproutemail_notificationemails.bcc',
            'sproutemail_notificationemails.listSettings',
            'sproutemail_notificationemails.fromName',
            'sproutemail_notificationemails.fromEmail',
            'sproutemail_notificationemails.replyToEmail',
            'sproutemail_notificationemails.singleEmail',
            'sproutemail_notificationemails.enableFileAttachments',
            'sproutemail_notificationemails.dateCreated',
            'sproutemail_notificationemails.dateUpdated',
            'sproutemail_notificationemails.fieldLayoutId'
        ]);

        $this->pluginHandle = Craft::$app->request->getBodyParam('criteria.pluginHandle');

        if ($this->pluginHandle !== null && $this->pluginHandle !== 'sprout-email') {
            $this->query->where(['sproutemail_notificationemails.pluginHandle' => $this->pluginHandle]);
        }

        return parent::beforePrepare();
    }

    /**
     * @inheritdoc
     */
    protected function statusCondition(string $status)
    {
        /**
         * To show disabled notification emails on integrated plugins
         */
        if ($this->pluginHandle !== 'sprout-email') {
            return ['elements.enabled' => ['0', '1']];
        }

        switch ($status) {
            case Element::STATUS_ENABLED:
                return ['elements.enabled' => '1'];
            case Element::STATUS_DISABLED:
                return ['elements.enabled' => '0'];
            default:
                return false;
        }
    }
}