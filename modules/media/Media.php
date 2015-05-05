<?php

namespace scorchsoft\scorchcore\modules\media;

use Yii;

/**
 * Class Media
 * @package kato\modules\media
 *
 * @property string $adminView
 * @property string $adminLayout
 */
class Media extends \kato\modules\media\Media
{
    public $controllerNamespace = 'scorchsoft\scorchcore\modules\media\controllers';

    public $adminView = 'index';
    public $adminLayout = null;

    public function init()
    {
        parent::init();

        Yii::setAlias('@media', dirname(__FILE__));
    }
}
