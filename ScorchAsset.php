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
        'bower_components/fontawesome/css/font-awesome.min.css',
        //'bower_components/bootstrap-material-design/dist/css/ripples.min.css',
        //'bower_components/bootstrap-material-design/dist/css/material.min.css',
        'css/sb-admin-2.css',
    ];

    public $js = [
        'js/bootstrap.min.js',
        'js/plugins/metisMenu/metisMenu.js',
        'js/plugins/html5sortable/html.sortable.min.0.1.3.js',
        //'bower_components//bootstrap-material-design/dist/js/ripples.min.js',
        //'bower_components//bootstrap-material-design/dist/js/material.min.js',
        'js/sb-admin-2.js',
    ];

    public $depends = [
        'kato\BowerAsset',
    ];

}
