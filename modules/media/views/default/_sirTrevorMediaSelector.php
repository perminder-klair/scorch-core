<?php
/**
 * @var common\modules\media\models\Media $media
 */
?>

<div class="modal fade" id="media-modal-2">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Select Media</h4>
            </div>
            <div class="modal-body media-select-body" id="media-select-body-content">

                <?php if ($model->media): ?>
                    <?php foreach ($model->media as $media): ?>
                        <div class="sir-trevor-media row" data-url="<?= $media->render(); ?>">
                            <div class="col-md-4 text-center">
                                <?= $media->render([
                                    'imgTag' => true,
                                    'width' => 90,
                                    'height' => 90,
                                    'class' => 'img-responsive'
                                ]); ?>
                            </div>
                            <div class="col-md-8">
                                <p class="media-title"><?php echo $media->title; ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div><!-- /.modal -->
