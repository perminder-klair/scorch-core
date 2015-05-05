<?php

use dosamigos\editable\Editable;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * @var scorchsoft\scorchcore\modules\media\models\Media $media
 * @var yii\web\View $this
 */

$titleOptions = [
    'name' => 'title',
    'value' => $media->title,
    'url' => \Yii::$app->urlManagerFrontend->createUrl(['/media/default/update-data', 'id' => $media->id]),
    'type' => 'text',
    'mode' => 'inline',
];

$statusOptions = [
    'name' => 'status',
    'value' => $media->statusLabel,
    'url' => \Yii::$app->urlManagerFrontend->createUrl(['/media/default/update-data', 'id' => $media->id]),
    'type' => 'select2',
    'mode' => 'pop',
];

$statusClientOptions = [
    'placement' => 'right',
    'select2' => [
        'width' => '124px'
    ],
    'source' => $media->statusDropDownList(),
];

$this->registerJs("
$('.lock-btn').on('click' , function () {
    $('#mediaModal-" . $media->id . "').modal('show');
});
$('.update-media').on('click', function () {
    var id = $(this).data('id');

    var locked = $('#mediaModal-' + id).find('#media-is_locked').val();
    var groups = [];
    $('#media-newallowedgroups input:checked').each(function() {
        groups.push(this.value);
    });

    var data = JSON.stringify({is_locked: locked, newAllowedGroups: groups});
    $.ajax({
        type     :'POST',
        cache    : false,
        //dataType: 'json',
        contentType: 'application/json; charset=utf-8',
        data    : data,
        url  : '" . \Yii::$app->urlManagerFrontend->createUrl(['/media/default/update-data', 'id' => $media->id]) . "',
        success  : function(response) {
            //$('#mediaModal-" . $media->id . "').modal('hide');
            //refresh page
            location.reload();
        }
    });
});
", \kato\web\View::POS_END, 'my-options');

$this->registerCss('
    .thumbnail .caption a {
        max-width: 100%;
        overflow: hidden;
    }
');

?>
<div class="col-sm-3 col-md-2" id="media-<?= $media->id ?>">
    <div class="thumbnail">
        <?php $isLocked = $media->is_locked==1?"fa-lock":"fa-unlock"; ?>
        <?= Html::a('<i class="fa ' . $isLocked . '"></i>', \Yii::$app->urlManagerFrontend->createUrl(['media/default/update', 'id' => $media->id]), [ //
            'class' => 'btn btn-default lock-btn',
            'onclick'=> "return false;",
        ]); ?>

        <?= Html::a('<i class="fa fa-times"></i>', null, [
            'title' => Yii::t('yii', 'Close'),
            'class' => 'btn btn-default delete-btn',
            //'data-original-title' => 'Delete',
            //'data-confirm' => 'Are you sure to delete this item?',
            'onclick'=>"
                 $.ajax({
                    type     :'POST',
                    cache    : false,
                    url  : '" . \Yii::$app->urlManagerFrontend->createUrl(['/media/default/remove-image', 'media_id' => $media->id, 'content_id' => $model->id, 'content_type' => $model->className()]) . "',
                    success  : function(response) {
                        $('#media-$media->id').remove();
                    }
                });return false;",
        ]); ?>

        <a href="<?php echo '/' . $media->source; ?>" <?php if ($media->mimeType === 'application/pdf') { ?>target="_blank"<?php } else { ?>data-lightbox="<?= $media->filename; ?>" data-title="<?= $media->title; ?>"<?php } ?>>
            <?= $media->render([
                'imgTag' => true,
                'width' => 90,
                'height' => 90,
                'class' => 'img-responsive'
            ]); ?>
        </a>

        <div class="caption">
            <h4>
                <?= Editable::widget(
                    ArrayHelper::merge($titleOptions, [
                        'options' => [
                            'id' => 'ed' . $media->id,
                        ],
                        'clientOptions' => [
                            'pk' => $media->id,
                        ],
                    ])
                ); ?>
            </h4>
            <div class="row">
                <div class="col-md-6">
                    <small><?= \kato\helpers\KatoBase::formatBytes($media->byteSize, 'MB', 3) ?></small>
                </div>
                <div class="col-md-6 status">
                    <?= Editable::widget(
                        ArrayHelper::merge($statusOptions, [
                            'options' => [
                                'id' => 'edw' . $media->id,
                            ],
                            'clientOptions' => ArrayHelper::merge($statusClientOptions, [
                                'pk' => $media->id . '2',
                            ]),
                        ])
                    ); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="mediaModal-<?= $media->id ?>" tabindex="-1" role="dialog" aria-labelledby="mediaModalLabel-<?= $media->id ?>" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Media Permissions</h4>
            </div>
            <div class="modal-body" style="padding: 10px 50px;">
                <?php if (isset($form)): ?>

                <?= $form->field($media, 'is_locked')->dropDownList([0 => 'No', 1 => 'Yes']) ?>

                <?= $form->field($media, 'newAllowedGroups')->checkboxList($media->listGroups()) ?>

                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <?= Html::a('Edit Tags', ['/media/default/update', 'id' => $media->id], ['class' => 'btn btn-success']) ?>
                <button type="button" class="btn btn-primary update-media" data-id="<?= $media->id ?>">Save changes</button>
            </div>
        </div>
    </div>
</div>

<?php if (isset($isNew)) { ?>
    <script type="text/javascript">
        jQuery('#ed<?= $media->id; ?>').editable(<?= Json::encode(ArrayHelper::merge($titleOptions, ['pk' => $media->id])); ?>);
        jQuery('#edw<?= $media->id; ?>').editable(
            <?= Json::encode(ArrayHelper::merge(ArrayHelper::merge($statusOptions, $statusClientOptions), ['pk' => $media->id])); ?>
        );
    </script>
<?php } ?>