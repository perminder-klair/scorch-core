<?php

namespace scorchsoft\scorchcore\modules\media\widgets;

use scorchsoft\scorchcore\modules\media\MediaAsset;
use Yii;
use yii\base\Widget;
use yii\bootstrap\ActiveForm;

class Media extends Widget
{
    public $model;

    public function init()
    {
        parent::init();

        $this->registerAsset();
    }

    public function run()
    {
        return $this->render('media', [
            'model' => $this->model,
        ]);
    }

    protected function registerAsset()
    {
        $view = $this->getView();
        MediaAsset::register($view);
    }
}
