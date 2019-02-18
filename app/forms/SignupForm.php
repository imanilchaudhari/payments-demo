<?php

namespace app\forms;

use Yii;
use app\components\EmailManager;
use app\models\User;
use yii\base\Model;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $email;
    public $password;
    public $verifyCode;

    public $full_name;
    public $password_confirm;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\app\models\User', 'message' => 'This email address has already been taken.'],

            [['password', 'password_confirm', 'full_name'], 'required'],
            [['password'], 'string', 'min' => 6],

            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha'],

            ['password_confirm', 'compare', 'compareAttribute' => 'password'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'verifyCode' => 'Verification Code',
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $name_array = explode(' ', $this->full_name);

        $user = new User();
        $user->email = $this->email;
        $user->generateUsername();
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateActivationKey();
        $user->first_name = reset($name_array);
        $user->last_name = end($name_array);

        if ($user->save()) {
            // send email for account activation
            EmailManager::sendAccountActivationMail($user);
            return $user;
        }
        return null;
    }
}
