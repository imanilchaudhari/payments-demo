<?php

namespace app\models;

use Yii;

class Customer extends User
{
    /**
     * Create new customer
     */
    public static function create($data = [])
    {
        $model = new Customer();
        $model->first_name = $data['first_name'];
        $model->last_name = $data['last_name'];
        $model->email = $data['email'];
        $model->password_repeat = $model->password = md5(Yii::$app->controller->generateKey(25));
        $model->status = 'active';
        if ($model->save()) {
            return $model;
        }

        return false;
    }

    /**
     * Check whether customer exists or not
     */
    public static function hasCustomer($email, $password = null, $api_mode = false)
    {
        $attributes = ['email' => $email, 'password' => md5($password)];
        if ($api_mode) {
            unset($attributes['password']);
        }

        $customer = self::find()->where($attributes)->one();
        if (empty($customer)) {
            return false;
        }
        return true;
    }
}
