<?php

namespace scorchsoft\scorchcore;

use Yii;

Yii::setAlias('scorch', __DIR__);

/**
 * This asset bundle provides the base javascript files for the Kato.
 */
class ScorchAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@scorch/assets';

    public $css = [
        'css/plugins/metisMenu/metisMenu.css',
        'css/sb-admin-2.css',
        'font-awesome-4.1.0/css/font-awesome.min.css',
    ];

    public $js = [
        'js/bootstrap.min.js',
        'js/plugins/metisMenu/metisMenu.js',
        'js/plugins/html5sortable/html.sortable.min.0.1.3.js',
        'js/sb-admin-2.js',
    ];

    public $depends = [
        'kato\BowerAsset',
    ];

}
