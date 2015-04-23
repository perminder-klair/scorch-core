<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kato\modules\tag\models\Tag;
use kartik\widgets\SwitchInput;

/**
 * @var kato\web\View $this
 * @var scorchsoft\scorchcore\modules\redirect\models\Redirect $model
 * @var yii\bootstrap\ActiveForm $form
 */
?>

<div class="row">
    <div class="col-lg-12">
        <?php $form = ActiveForm::begin([
            'layout' => 'horizontal',
            'fieldConfig' => [
                'horizontalCssClasses' => [
                    'label' => 'col-sm-4',
                    'offset' => 'col-sm-offset-4',
                    'wrapper' => 'col-sm-8',
                    'error' => '',
                    'hint' => '',
                ],
            ],
        ]); ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#info" data-toggle="tab">Basic Info</a></li>
                </ul>
            </div>
            <div class="panel-body">
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane fade in active redirects-form" id="info">

                        <?= $form->field($model, 'old_url')->textInput(['maxlength' => 255]) ?>

                        <?= $form->field($model, 'new_url')->textInput(['maxlength' => 255]) ?>

                        <?= $form->field($model, 'active')->widget(SwitchInput::classname(), [
                            'pluginOptions' => [
                                'size' => 'small'
                            ],
                        ]) ?>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success col-sm-offset-2' : 'btn btn-primary col-sm-offset-2']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
