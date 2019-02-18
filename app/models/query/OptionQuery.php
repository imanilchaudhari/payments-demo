<?php

namespace app\models\query;

use Yii;

/**
 * This is the ActiveQuery class for [[\app\models\Option]].
 *
 * @see \app\models\Option
 */
class OptionQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Option[]|array
     */
    public function all($db = null)
    {

        return Yii::$app->db->cache(function ($db) {
            return parent::all($db);
        });
    }

    /**
     * @inheritdoc
     * @return Option|array|null
     */
    public function one($db = null)
    {
        return Yii::$app->db->cache(function ($db) {
            return parent::one($db);
        });
    }
}
