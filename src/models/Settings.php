<?php
/**
 * @link      https://sprout.barrelstrengthdesign.com
 * @copyright Copyright (c) Barrel Strength Design LLC
 * @license   https://craftcms.github.io/license
 */

namespace barrelstrength\sproutbaseemail\models;

use barrelstrength\sproutbase\base\SharedPermissionsInterface;
use barrelstrength\sproutbase\base\SproutSettingsInterface;
use barrelstrength\sproutbaseemail\emailtemplates\BasicTemplates;
use Craft;
use craft\base\Model;

/**
 * @property array $sharedPermissions
 * @property array $settingsNavItems
 */
class Settings extends Model implements SproutSettingsInterface, SharedPermissionsInterface
{
    /**
     * @var string
     */
    public $pluginNameOverride = '';

    /**
     * @var bool
     */
    public $enableNotificationEmails = true;

    /**
     * @var null
     */
    public $emailTemplateId = BasicTemplates::class;

    /**
     * @var int
     */
    public $enablePerEmailEmailTemplateIdOverride = 0;

    /**
     * @inheritdoc
     */
    public function getSettingsNavItems(): array
    {
        $navItems['general'] = [
            'label' => Craft::t('sprout-base-email', 'General'),
            'url' => 'sprout-email/settings/general',
            'selected' => 'general',
            'template' => 'sprout-base-email/settings/general'
        ];
        $navItems['mailers'] = [
            'label' => Craft::t('sprout-base-email', 'Mailers'),
            'url' => 'sprout-email/settings/mailers',
            'selected' => 'mailers',
            'template' => 'sprout-base-email/settings/mailers'
        ];
//            'campaigntypes' => [
//                'label' => Craft::t('sprout-base-email', 'Campaigns'),
//                'url' => 'sprout-email/settings/campaigntypes',
//                'selected' => 'campaigntypes',
//                'template' => 'sprout-base-email/settings/campaigntypes',
//                'settingsForm' => false
//            ],
        $navItems['notifications'] = [
            'label' => Craft::t('sprout-base-email', 'Notifications'),
            'url' => 'sprout-email/settings/notifications',
            'selected' => 'notifications',
            'template' => 'sprout-base-email/settings/notifications'
        ];

        $isInstalledSentEmail = Craft::$app->getPlugins()->getPlugin('sprout-sent-email');

        if (Craft::$app->getUser()->checkPermission('sproutEmail-viewSentEmail')) {
            $navItems['sent-email'] = [
                'label' => Craft::t('sprout-base-email', 'Sent Emails'),
                'url' => $isInstalledSentEmail
                    ? 'sprout-sent-email/settings/sent-email'
                    : 'sprout-email/settings/sent-email',
                'selected' => 'sent-email',
                'template' => 'sprout-base-sent-email/settings/sent-email'
            ];
        }

        $navItems['integrationsHeading'] = [
            'heading' => Craft::t('sprout-base-email', 'Integrations'),
        ];
        $navItems['mailing-lists'] = [
            'label' => Craft::t('sprout-base-email', 'Mailing Lists'),
            'url' => 'sprout-email/settings/mailing-lists',
            'selected' => 'mailing-lists',
            'template' => 'sprout-base-email/settings/mailing-lists'
        ];

        return $navItems;
    }

    /**
     * Shared permissions they may be prefixed by another plugin. Before checking
     * these permissions the plugin name will be determined from the URL and appended.
     *
     * @return array
     * @example
     * /admin/sprout-reports/page => sproutReports-viewReports
     * /admin/sprout-forms/page => sproutForms-viewReports
     *
     */
    public function getSharedPermissions(): array
    {
        return [
            'viewNotifications',
            'editNotifications'
        ];
    }
}