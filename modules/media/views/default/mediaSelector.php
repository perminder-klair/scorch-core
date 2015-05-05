<?php

use scorchsoft\scorchcore\modules\media\models\Media;

/**
 * @var scorchsoft\scorchcore\modules\media\models\Media $media
 */
?>

<button type="button" class="btn btn-primary btn-lg" id="show-media-selector" style="width: 70%; margin: 0 auto;">
    or select media from library
</button>

<div class="modal fade" id="media-modal-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Select Media</h4>
                <input type="text" placeholder="Search for media" name="media-search-input" class="form-control" id="media-search-input">
            </div>
            <div class="modal-body media-select-body" id="media-select-body-content">

                <?php if ($mediaList = Media::find()->limit(30)->all()): ?>
                    <?php foreach ($mediaList as $media): ?>
                        <div class="single-media-row row" id="single-media-id-<?php echo $media->id; ?>">
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

<?php
$this->registerJs("

    function attachMedia() {
        $('.single-media-row').bind('click' , function () {
            var imageId = $(this).attr('id').substring(16);
             $.ajax({
                type     :'POST',
                cache    : false,
                url  : '" . \Yii::$app->urlManagerFrontend->createUrl(['/media/default/assign', 'content_id' => $model->id, 'content_type' => $model->className()]) . "&media_id=' + imageId,
                success  : function(response) {
                   $('#media-modal-1').modal('hide');
                   location.reload();
                }
            });
        });
    }

    $(document).ready(function() {
        attachMedia();

        $('#show-media-selector').on('click' , function () {
            $('#media-modal-1').modal('show');
        });

        var delay = (function(){
          var timer = 0;
          return function(callback, ms){
            clearTimeout (timer);
            timer = setTimeout(callback, ms);
          };
        })();

        $('#media-search-input').keyup(function() {
            var thisData = $(this);

            delay(function () {
                $.ajax({
                    type     :'POST',
                    cache    : false,
                    url  : '/media/default/media-search?search_text=' + thisData.val(),
                    success  : function(response) {
                        $('#media-select-body-content').empty();
                        var images = JSON.parse(response);

                        if(images.length === 0) {
                            $('#media-select-body-content').append('<h4>There is no results</h4>');
                        }

                        for (var i = 0, len = images.length; i < len; i++) {
                            var append_content = '<div class=\"single-media-row row\" id=\"single-media-id-'+images[i].image_id+'\"><div class=\"col-md-4 text-center\"><img alt=\"\" src=\"'+images[i].image_link+'\"></div><div class=\"col-md-8\"><p class=\"media-title\">'+images[i].image_title+'</p></div></div>';
                            $('#media-select-body-content').append(append_content);
                        }

                        attachMedia();
                    }
                });
            }, 2000);
        });
    });

");
