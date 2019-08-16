<?php

namespace barrelstrength\sproutbaseemail\elements\db;

use barrelstrength\sproutbaseemail\services\NotificationEmails;
use craft\elements\db\ElementQuery;
use craft\base\Element;
use Craft;

class NotificationEmailQuery extends ElementQuery
{
    /**
     * @var string
     */
    public $viewContext;
    public $notificationEmailBaseUrl;

    /**
     * @inheritdoc
     */
    protected function beforePrepare(): bool
    {
        $this->joinElementTable('sproutemail_notificationemails');

        $this->query->select([
            'sproutemail_notificationemails.viewContext',
            'sproutemail_notificationemails.titleFormat',
            'sproutemail_notificationemails.sendRule',
            'sproutemail_notificationemails.sendMethod',
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

        /** @deprecated
         *  We only need the check for console request because we use
         *  saveNotification m180314_161540_craft2_to_craft3. We can remove
         *  the check for the console request once we set a minVersionRequired
         *  and get folks upgraded past that migration.
         */
        if (!Craft::$app->getRequest()->getIsConsoleRequest()) {
            $this->viewContext = Craft::$app->request->getBodyParam('criteria.viewContext');

            if ($this->viewContext !== null && $this->viewContext !== NotificationEmails::DEFAULT_VIEW_CONTEXT) {
                $this->query->where(['sproutemail_notificationemails.viewContext' => $this->viewContext]);
            }
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
        if ($this->viewContext !== NotificationEmails::DEFAULT_VIEW_CONTEXT) {
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