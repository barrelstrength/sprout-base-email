<?php

namespace barrelstrength\sproutbaseemail\web\assets\base;

use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

class NotificationAsset extends AssetBundle
{
    public function init()
    {
        $this->depends = [
            CpAsset::class,
        ];

        $this->sourcePath = '@sproutbaseemail/web/assets/base/dist';

        $this->js = [
            'js/notification.js',
            'js/sproutmodal.js'
        ];

        $this->css = [
            'css/modal.css'
        ];

        parent::init();
    }
}