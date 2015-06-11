<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use \kato\modules\tag\models\Tag;

$tag = new Tag();

/**
 * @var yii\web\View $this
 * @var common\modules\media\models\Media $model
 * @var yii\bootstrap\ActiveForm $form
 */

$this->registerJs("$('.delete-item').on('click', function () { $('#deleteModal').modal(); });");
?>

<a href="<?php echo '/' . $model->source; ?>"
   <?php if ($model->mimeType === 'application/pdf') { ?>target="_blank"<?php } else { ?>data-lightbox="<?= $model->filename; ?>" data-title="<?= $model->title; ?>"<?php } ?>
   class="center-block"
   style="width: 90px; height: auto; margin: 15px auto;">
    <?= $model->render([
        'imgTag' => true,
        'width' => 90,
        'height' => 90,
        'class' => 'img-responsive'
    ]); ?>
</a>
<a href="<?php echo '/' . $model->source; ?>" target="_blank" style="text-align: center;">
    <p><?= $model->title; ?></p>
</a>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#info" data-toggle="tab">Basic Info</a></li>
                    <li><a href="#usage" data-toggle="tab">Usage</a></li>
                </ul>
            </div>
            <div class="panel-body">
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane fade in active" id="info">
                        <?php $form = ActiveForm::begin(); ?>

                        <?= $form->field($model, 'title')->textInput(); ?>

                        <?= $form->field($model, 'status')->dropDownList($model->listStatus()); ?>

                        <?= $form->field($model, 'tags')->widget(Select2::classname(), [
                            'language' => 'en',
                            'options' => [
                                'multiple' => true,
                                'placeholder' => 'Tags ...'
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'tags' => $tag->listTags($model->className()),
                            ],
                        ]); ?>

                        <?= $form->field($model, 'is_locked')->dropDownList([0 => 'No', 1 => 'Yes']) ?>

                        <?= $form->field($model, 'newAllowedGroups')->checkboxList($model->listGroups()) ?>

                        <div class="form-group">
                            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                            <?= Html::button('Delete', ['class' => 'btn btn-danger delete-item']) ?>
                        </div>

                        <?php ActiveForm::end(); ?>
                    </div>
                    <div class="tab-pane fade in" id="usage">
                        <?php if ($model->contentItems): ?>
                            <ul>
                            <?php foreach ($model->contentItems as $item):
                                $function = new \ReflectionClass($item::className());
                            ?>
                                <li><strong><?= $function->getShortName() ?></strong> - (ID: <?= $item->id ?>) <?= $item->title ?></li>
                            <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Delete media, are you sure?</h4>
            </div>
            <div class="modal-body">
                <?php if ($model->contentItems): ?>
                    <p>This media item is used in the following areas:</p>
                    <ul>
                        <?php foreach ($model->contentItems as $item):
                            $function = new \ReflectionClass($item::className());
                            ?>
                            <li><strong><?= $function->getShortName() ?></strong> - (ID: <?= $item->id ?>) <?= $item->title ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                <p>Are you sure, you want to delete this media file?</p>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                <?= Html::a('Yes', ['/media/default/delete', 'id' => $model->id] , ['class' => 'btn btn-primary']); ?>
            </div>
        </div>
    </div>
</div>
