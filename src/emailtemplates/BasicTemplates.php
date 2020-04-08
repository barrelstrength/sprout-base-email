<?php

namespace barrelstrength\sproutbaseemail\emailtemplates;

use barrelstrength\sproutbaseemail\base\EmailTemplates;
use Craft;

/**
 * Class BasicTemplates
 */
class BasicTemplates extends EmailTemplates
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return Craft::t('sprout-base-email', 'Basic Notification (Sprout Email)');
    }

    public function getTemplateRoot(): string
    {
        return Craft::getAlias('@sproutbaseemail/templates');
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return '_components/emailtemplates/basic';
    }
}



