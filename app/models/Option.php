<?php

namespace app\models;

use Yii;
use app\helpers\Json;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%option}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $value
 * @property string $label
 * @property string $group
 *
 * @author Anil Chaudhari <anil@proshore.eu>
 * @since 0.1.0
 */
class Option extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%option}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'value'], 'required'],
            ['value', 'string'],
            [['name', 'label', 'group'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'value' => Yii::t('app', 'Value'),
            'label' => Yii::t('app', 'Label'),
            'group' => Yii::t('app', 'Group'),
        ];
    }

    /**
     * @inheritdoc
     * @return \app\models\query\OptionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\OptionQuery(get_called_class());
    }

    /**
     * Get option value.
     * The return value array|boolean|string depends on value.
     * If value is string then the return value is a string.
     * If value is array|object then return value is array.
     * If name not found in table then it will return false.
     *
     * @param string $name
     * @return string|array|boolean
     */
    public static function get($name)
    {
        /* @var $model \app\models\Option */
        $model = static::findOne(['name' => $name]);

        if ($model) {
            if (Json::isJson($model->value)) {
                return Json::decode($model->value);
            }

            return $model->value;
        }

        return false;
    }

    /**
     * Add new option, required name and value.
     * If value is array or object, it will be converted to json with Json::encode.
     *
     * @param string $name
     * @param string $value
     * @param string|array $label
     * @param string $group
     * @return bool
     */
    public static function set($name, $value, $label = null, $group = null)
    {
        if (is_array($value) || is_object($value)) {
            $value = Json::encode($value);
        }

        if (static::get($name) !== null) {
            return static::up($name, $value);
        }

        $model = new Option([
            'name' => $name,
            'value' => $value,
            'label' => $label,
            'group' => $group,
        ]);

        return $model->save();
    }

    /**
     * Update option with name as key.
     *
     * @param string $name
     * @param string|array $value
     * @return bool
     */
    public static function up($name, $value)
    {
        /* @var $model \app\models\Option */
        $model = static::findOne(['name' => $name]);

        if (is_array($value) || is_object($value)) {
            $model->value = Json::encode($value);
        } else {
            $model->value = $value;
        }

        return $model->save();
    }

    /**
     * Get menu item to render in admin sidebar left.
     *
     * @param int $position
     * @return array
     */
    public static function getMenus($position = 30)
    {
        $items[$position] = ['label' => Yii::t('app', 'Settings'), 'icon' => 'fa fa-cogs'];
        $items[$position]['items'] = static::getSubmenus();

        return $items;
    }

    /**
     * The option will be grouped into group to create new submenu item.
     *
     * @return array|null
     */
    protected static function getSubmenus()
    {
        /* @var $model \app\models\Option */
        $models = static::find()
            ->groupBy('group')
            ->andWhere(['<>', 'group', ''])
            ->orderBy(['id' => SORT_ASC])
            ->all();
        $items = null;

        foreach ($models as $model) {
            $items[] = [
                'icon' => 'fa fa-circle-o',
                'label' => Yii::t('app', ucwords($model->group)),
                'url' => ['/setting/group/', 'id' => strtolower($model->group)],
                'visible' => Yii::$app->user->can('administrator'),
            ];
        }

        return $items;
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        Yii::$app->cache->flush();
    }
}
