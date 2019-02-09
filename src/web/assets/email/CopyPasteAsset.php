<?php

namespace barrelstrength\sproutbaseemail\web\assets\email;

use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * Class CopyPasteAsset
 *
 * @package barrelstrength\sproutemail\web\assets\email
 */
class CopyPasteAsset extends AssetBundle
{
    public function init()
    {
        $this->sourcePath = '@sproutbaseemail/web/assets/email/dist';

        $this->depends = [
            CpAsset::class,
        ];

        $this->js = [
            'copypaste/js/copypaste.js'
        ];

        $this->css = [
            'copypaste/css/copypaste.css'
        ];

        parent::init();
    }
}