<?php

namespace scorchsoft\scorchcore\modules\media\models;

use Yii;
use common\models\User;
use kato\modules\media\models\Media as MediaModel;
use kato\modules\media\models\ContentMedia;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * This is the model class for table "kato_media".
 *
 * @property string $id
 * @property string $title
 * @property string $filename
 * @property string $source
 * @property string $source_location
 * @property string $create_time
 * @property string $extension
 * @property string $mimeType
 * @property string $byteSize
 * @property integer $status
 * @property string $content_type
 * @property string $baseSource
 * @property string $baseSourceUrl
 * @property integer $is_locked
 * @property string $allowed_groups
 * @property string $tags
 */
class Media extends MediaModel
{
    const IS_LOCKED = 1;
    const IS_NOT_LOCKED = 0;

    public $newAllowedGroups;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['filename', 'source'], 'required'],
            [['tags'], 'string'],
            [['create_time', 'media_type', 'title', 'is_locked', 'newAllowedGroups', 'tags'], 'safe'],
            [['byteSize', 'status'], 'integer'],
            [['filename', 'source', 'source_location', 'title'], 'string', 'max' => 255],
            [['extension', 'mimeType'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'filename' => 'Filename',
            'source' => 'Source',
            'source_location' => 'Source Location',
            'create_time' => 'Create Time',
            'extension' => 'Extension',
            'mimeType' => 'Mime Type',
            'byteSize' => 'Byte Size',
            'status' => 'Status',
            'media_type' => 'Media Type',
            'newAllowedGroups' => 'Allowed Groups',
            'is_locked' => 'Is Locked',
            'tags' => 'Tags',
        ];
    }

    public function behaviors()
    {
        return [
            'normalizeTags' => [
                'class' => 'kato\behaviors\NormalizeTags',
                'attribute' => 'tags',
                'updateTags' => true,
                'tagType' => self::className(),
            ],
        ];
    }

    /**
     * Actions to be taken before saving the record.
     * @param bool $insert
     * @return bool whether the record can be saved
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (!$this->isNewRecord) {
                $this->setAllowedGroups();
            }

            return true;
        }
        return false;
    }

    public function afterFind()
    {
        $this->newAllowedGroups = Json::decode($this->allowed_groups);

        parent::afterFind();
    }

    public function setAllowedGroups()
    {
        if (!is_array($this->newAllowedGroups)) {
            $this->newAllowedGroups = [];
        }

        //make sure admin is in array
        if (!in_array("admin", $this->newAllowedGroups)) {
            $this->newAllowedGroups[] = 'admin';
        }

        $this->allowed_groups = Json::encode($this->newAllowedGroups);
    }

    public function isLocked()
    {
        if ($this->is_locked === 1) {
            //if current user's role is not in array, return as locked
            if (!is_array($this->newAllowedGroups)) {
                return true;
            }

            if (is_null(Yii::$app->user->identity)) {
                //not logged in so not allowed
                return true;
            }

            if (!in_array(Yii::$app->user->identity->role, $this->newAllowedGroups)) {
                return true;
            }
        }

        return false;
    }

    public function listGroups()
    {
        $user = new User();
        $roles = $user->listRoles();
        unset($roles['admin']); //don't show admin in list

        return $roles;
    }

    private function checkPermissionAllowed()
    {
        if ($this->is_locked === self::IS_LOCKED) {
            //if current user's role is not in array, return as locked
            if (!is_array($this->newAllowedGroups)) {
                return false;
            }

            if (is_null(Yii::$app->user->identity)) {
                //not logged in so not allowed
                return false;
            }

            if (in_array(Yii::$app->user->identity->role, $this->newAllowedGroups)) {
                return true;
            }

            return false;
        }

        return true;
    }

    public function getMediaContent()
    {
        return $this->hasMany(ContentMedia::className(), ['media_id' => 'id']);
    }

    public function getContentItems()
    {
        $items = [];
        if ($this->mediaContent) {
            foreach ($this->mediaContent as $content) {
                $className = $content->content_type;

                if ($item = $className::find()->where(['id' => $content->content_id])->one()) {
                    $items[] = $item;
                }
            }
        }

        return $items;
    }

    public function tagFilter()
    {
        $html = '';

        if (strlen($this->tags) < 1) {
            return $html;
        }

        $tags = explode(', ', $this->tags);
        if (is_array($tags)) {
            foreach ($tags  as $key => $val) {
                $html .= Html::a($val, ['/media/default/index', 'MediaSearch' => ['tags' => $val]]) . ' ';
            }
        }

        return $html;
    }

    /**
     * Renders media
     * @param array $data
     * @return bool|string
     */
    public function render($data = [])
    {
        //if file local does not exists
        if (!file_exists($this->baseSource)) {
            return '#';
        }

        //check if it's pdf file
        if ($this->mimeType === 'application/pdf') {
            return $this->renderPdf($data);
        } else {
            //check if private and has permission
            if ($this->checkPermissionAllowed() === false) {
                //return dummy image not allowed
                return '/theme-assets/locked-image.png';
            }

            return $this->renderImage($data);
        }
    }

    /**
     * Serve files only if allowed, return false if not
     * @param $source
     * @return array|bool
     */
    public static function serveFile($source)
    {
        /**
         * @var $file \common\modules\media\models\Media
         */
        if ($file = self::find()->where(['source' => $source])->one()) {
            //check if current user has permission to view this file
            if ($file->checkPermissionAllowed() === false) {
                return false;
            } else {
                //if file local does not exists
                if (!file_exists($file->baseSource)) {
                    return false;
                }

                //check if it's pdf file
                if ($file->mimeType === 'application/pdf') {
                    return [
                        'mimeType' => $file->mimeType,
                        'source' => $file->renderPdf([]),
                    ];
                } else {
                    return [
                        'mimeType' => $file->mimeType,
                        'source' => $file->renderImage([]),
                    ];
                }
            }
        }

        return false;
    }
}
