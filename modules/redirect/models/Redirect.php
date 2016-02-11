<?php

namespace scorchsoft\scorchcore\modules\redirect\models;

use Yii;

/**
 * This is the model class for table "redirects".
 *
 * @property integer $id
 * @property string $old_url
 * @property string $new_url
 * @property string $create_time
 * @property string $update_time
 * @property integer $active
 * @property integer $deleted
 */
class Redirect extends \yii\db\ActiveRecord
{

    const STATUS_NOT_PUBLISHED = 0;
    const STATUS_PUBLISHED = 1;

    public $title;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'redirect';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['old_url'], 'required'],
            [['active', 'deleted'], 'integer'],
            [['create_time', 'update_time',], 'safe'],
            [['old_url', 'new_url'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'old_url' => 'Old Url',
            'new_url' => 'New Url',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'active' => 'Active',
            'deleted' => 'Deleted',
        ];
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    \kato\ActiveRecord::EVENT_BEFORE_INSERT => ['create_time', 'update_time'],
                    \kato\ActiveRecord::EVENT_BEFORE_UPDATE => ['update_time'],
                ],
                'value' => new \yii\db\Expression('NOW()'),
            ],
            'softDelete' => [
                'class' => 'kato\behaviors\SoftDelete',
                'attribute' => 'deleted',
                'safeMode' => false,
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

        if ($this->isNewRecord){
            $this->active = 0;
        }

        if (parent::beforeSave($insert)) {
            $this->old_url = $this->trimUrl($this->old_url);
            $this->new_url = $this->trimUrl($this->new_url);

            return true;
        }
        return false;
    }

    private function trimUrl($url)
    {
        $url = rtrim($url, "/");
        $url = ltrim($url, "/");
        return $url;
    }

    /**
     * Finds and redirect to page
     * @return bool
     */
    public static function checkRedirect()
    {
        $model = new self();

        $url = $model->trimUrl(Yii::$app->request->pathInfo);

        /**
         * @var \scorchsoft\scorchcore\modules\redirect\models\Redirect $page
         */
        //find if redirect exists
        if ($page = self::find()
            ->where(['old_url' => $url, 'active' => static::STATUS_PUBLISHED])
            ->one()) {

            if(!is_null($page->new_url) && strlen($page->new_url) != 0) {
                //new url is specified, redirect to it
                header("HTTP/1.1 301 Moved Permanently");
                header("Location: /" . $page->new_url);
                exit;
            }

            return false;
        }

        return false;
    }
}
