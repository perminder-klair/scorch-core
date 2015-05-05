<?php

namespace scorchsoft\scorchcore;

use kato\modules\media\models\ContentMedia;
use scorchsoft\scorchcore\modules\media\models\Media;

class ActiveRecord extends \kato\ActiveRecord
{
    /**
     * Attached Content Media, by type
     * @return static
     */
    public function getContentMedia()
    {
        return $this->hasMany(ContentMedia::className(), ['content_id' => 'id'])
            ->where('content_type = :type', [':type' => $this->className()]);
    }

    /**
     * Relate Media
     * Usage: $model->media();
     * @param null $type
     * @return static
     */
    public function getMedia($type = null)
    {
        $media = $this->hasMany(Media::className(), ['id' => 'media_id']);
        if ($type !== null) {
            $media->where('media_type = :type', [':type' => $type]);
        }
        $media->via('contentMedia');

        return $media;
    }
}