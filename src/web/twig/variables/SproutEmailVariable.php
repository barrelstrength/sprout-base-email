<?php

namespace barrelstrength\sproutbaseemail\web\twig\variables;

use barrelstrength\sproutbaseemail\base\EmailTemplates;
use barrelstrength\sproutbaseemail\base\Mailer;
use barrelstrength\sproutbaseemail\emailtemplates\BasicTemplates;
use barrelstrength\sproutbaseemail\SproutBaseEmail;
use barrelstrength\sproutbasesentemail\elements\SentEmail;
use Craft;
use craft\helpers\UrlHelper;
use yii\base\Exception;

class SproutEmailVariable
{
    /**
     * @param $mailer
     *
     * @return Mailer
     * @throws Exception
     */
    public function getMailer($mailer): Mailer
    {
        return SproutBaseEmail::$app->mailers->getMailerByName($mailer);
    }

    public function getSentEmailById($sentEmailId)
    {
        return Craft::$app->getElements()->getElementById($sentEmailId, SentEmail::class);
    }

    /**
     * Returns a Campaign Email Share URL and Token
     *
     * @param $emailId
     * @param $campaignTypeId
     *
     * @return array|string
     */
    public function getCampaignEmailShareUrl($emailId, $campaignTypeId)
    {
        return UrlHelper::actionUrl('sprout-campaign/campaign-email/share-campaign-email', [
            'emailId' => $emailId,
            'campaignTypeId' => $campaignTypeId
        ]);
    }

    /**
     * Get the available Email Template Options
     *
     * @param null $notificationEmail
     *
     * @return array
     */
    public function getEmailTemplateOptions($notificationEmail = null): array
    {
        $defaultEmailTemplates = new BasicTemplates();

        $templates = SproutBaseEmail::$app->emailTemplates->getAllEmailTemplates();

        $templateIds = [];
        $options = [
            [
                'label' => Craft::t('sprout-base-email', 'Select...'),
                'value' => ''
            ]
        ];

        /**
         * Build our options
         *
         * @var EmailTemplates $template
         */
        foreach ($templates as $template) {
            $type = get_class($template);

            $options[] = [
                'label' => $template->getName(),
                'value' => $type
            ];
            $templateIds[] = $type;
        }

        $templateFolder = null;
        $settings = SproutBaseEmail::$app->settings->getEmailSettings();

        $templateFolder = $notificationEmail->emailTemplateId ?? $settings->emailTemplateId ?? $defaultEmailTemplates->getPath();

        $options[] = [
            'optgroup' => Craft::t('sprout-base-email', 'Custom Template Folder')
        ];

        if (!in_array($templateFolder, $templateIds, false) && $templateFolder != '') {
            $options[] = [
                'label' => $templateFolder,
                'value' => $templateFolder
            ];
        }

        $options[] = [
            'label' => Craft::t('sprout-base-email', 'Add Custom'),
            'value' => 'custom'
        ];

        return $options;
    }
}