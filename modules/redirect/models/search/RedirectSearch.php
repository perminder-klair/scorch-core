<?php

namespace scorchsoft\scorchcore\modules\redirect\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use scorchsoft\scorchcore\modules\redirect\models\Redirect;

/**
 * RedirectSearch represents the model behind the search form about `scorchsoft\scorchcore\modules\redirect\models\Redirect`.
 */
class RedirectSearch extends Model
{
    public $id;
    public $content_id;
    public $content_type;
    public $old_url;
    public $new_url;
    public $tags;
    public $create_time;
    public $update_time;
    public $active;
    public $deleted;

    public function rules()
    {
        return [
            [['id', 'content_id', 'active', 'deleted'], 'integer'],
            [['content_type', 'old_url', 'new_url', 'tags', 'create_time', 'update_time'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content_id' => 'Content ID',
            'content_type' => 'Content Type',
            'old_url' => 'Old Url',
            'new_url' => 'New Url',
            'tags' => 'Tags',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'active' => 'Active',
            'deleted' => 'Deleted',
        ];
    }

    public function search($params)
    {
        $query = Redirect::find();
        $query->andWhere(['deleted' => 0]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'content_id' => $this->content_id,
            'create_time' => $this->create_time,
            'update_time' => $this->update_time,
            'active' => $this->active,
            'deleted' => $this->deleted,
        ]);

        $query->andFilterWhere(['like', 'content_type', $this->content_type])
            ->andFilterWhere(['like', 'old_url', $this->old_url])
            ->andFilterWhere(['like', 'new_url', $this->new_url])
            ->andFilterWhere(['like', 'tags', $this->tags]);

        return $dataProvider;
    }

    protected function addCondition($query, $attribute, $partialMatch = false)
    {
        if (($pos = strrpos($attribute, '.')) !== false) {
            $modelAttribute = substr($attribute, $pos + 1);
        } else {
            $modelAttribute = $attribute;
        }

        $value = $this->$modelAttribute;
        if (trim($value) === '') {
            return;
        }
        if ($partialMatch) {
            $query->andWhere(['like', $attribute, $value]);
        } else {
            $query->andWhere([$attribute => $value]);
        }
    }
}
