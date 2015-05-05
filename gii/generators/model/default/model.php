<?php
/**
 * This is the template for generating the model class of a specified table.
 *
 * @var yii\web\View $this
 * @var yii\gii\generators\model\Generator $generator
 * @var string $tableName full table name
 * @var string $className class name
 * @var yii\db\TableSchema $tableSchema
 * @var string[] $labels list of attribute labels (name => label)
 * @var string[] $rules list of validation rules
 * @var array $relations list of relations (name => relation declaration)
 */

echo "<?php\n";
?>

namespace <?= $generator->ns ?>;

/**
* This is the model class for table "<?= $tableName ?>".
*
<?php foreach ($tableSchema->columns as $column): ?>
    * @property <?= "{$column->phpType} \${$column->name}\n" ?>
<?php endforeach; ?>
<?php if (!empty($relations)): ?>
    *
    <?php foreach ($relations as $name => $relation): ?>
        * @property <?= $relation[1] . ($relation[2] ? '[]' : '') . ' $' . lcfirst($name) . "\n" ?>
    <?php endforeach; ?>
<?php endif; ?>
*/
class <?= $className ?> extends <?= '\\' . ltrim($generator->baseClass, '\\') . "\n" ?>
{

    const STATUS_NOT_PUBLISHED = 0;
    const STATUS_PUBLISHED = 1;

    /**
    * @inheritdoc
    */
    public static function tableName()
    {
        return '<?= $tableName ?>';
    }

    /**
    * @inheritdoc
    */
    public function rules()
    {
        return [<?= "\n            " . implode(",\n            ", $rules) . "\n        " ?>];
    }

    /**
    * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
    <?php foreach ($labels as $name => $label): ?>
        <?= "'$name' => '" . addslashes($label) . "',\n" ?>
    <?php endforeach; ?>
    ];
    }
    <?php foreach ($relations as $name => $relation): ?>

        /**
        * @return \yii\db\ActiveQuery
        */
        public function get<?= $name ?>()
        {
        <?= $relation[0] . "\n" ?>
        }
    <?php endforeach; ?>

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
            'normalizeTags' => [
                'class' => 'kato\behaviors\NormalizeTags',
                'attribute' => 'tags',
                'updateTags' => true,
                'tagType' => self::className(),
            ],
                'defaultTitle' => [
                'class' => 'kato\behaviors\DefaultTitle',
                'attribute' => 'title',
                'defaultPrefix' => '<?= $className ?>',
            ],
        ];
    }

}
