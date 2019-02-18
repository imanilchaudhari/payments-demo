<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "customer".
 *
 * @property int $id
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $password_hash
 * @property string $auth_key
 * @property string $activation_key
 * @property string $password_reset_token
 * @property string $first_name
 * @property string $last_name
 * @property string $dob
 * @property string $gender
 * @property string $telephone
 * @property string $status
 * @property int $is_subscribed
 * @property int $is_taggable
 * @property string $created_date
 * @property string $modified_date
 */
class User extends ActiveRecord implements IdentityInterface
{
    const EVENT_AFTER_PASSWORD_RESET = 'afterPasswordReset';
    const EVENT_AFTER_PROFILE_UPDATE = 'afterProfileUpdate';

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_DELETED = 'deleted';

    public $password_new;
    public $password_old;
    public $password_repeat;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%customer}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_date', 'modified_date'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'modified_date',
                ],
                'value' => date('Y-m-d H:i:s')
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'email', 'first_name', 'last_name', 'password_hash', 'password_reset_token', 'telephone'], 'string', 'max' => 255],
            [['email'], 'required'],
            [['email'], 'unique'],
            ['email', 'email'],
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'string', 'min' => 3, 'max' => 32],
            ['email', 'filter', 'filter' => 'trim'],
            [['is_subscribed', 'is_taggable'], 'integer'],
            [['dob', 'profile_image', 'created_date', 'modified_date'], 'safe'],
            [['status'], 'string'],
            [['activation_key'], 'string', 'max' => 60],
            [['gender'], 'string', 'max' => 10],
            ['auth_key', 'string', 'max' => 32],
            [['password_new', 'password_old', 'password_repeat'], 'required', 'on' => 'resetPassword'],
            ['password', 'required', 'on' => 'register'],
            ['password', 'string', 'min' => 6, 'max' => 255],
            ['password_old', 'passwordValidation'],
            ['password_repeat', 'compare', 'compareAttribute' => 'password_new'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'email' => 'Email',
            'password' => 'Password',
            'auth_key' => 'Auth Key',
            'activation_key' => 'Activation Key',
            'password_reset_token' => 'Password Reset Token',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'dob' => 'Dob',
            'gender' => 'Gender',
            'telephone' => 'Telephone',
            'status' => 'Status',
            'is_subscribed' => 'Is Subscribed',
            'is_taggable' => 'Is Taggable',
            'created_date' => 'Created Date',
            'modified_date' => 'Modified Date',
        ];
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\UserQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['auth_key' => $token]);
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }

    /**
     * Hash with md5 string
     * @param string $string
     * @return string with md5 encrypted
     */
    public static function hash($string)
    {
        return md5($string);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password == self::hash($password);
        // return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = self::hash($password);
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generateActivationKey()
    {
        $this->activation_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * Validate password when resetting
     */
    public function passwordValidation()
    {
        $user = static::findOne(Yii::$app->user->id);

        if (!$user || !$user->validatePassword($this->password_old)) {
            $this->addError('password_old', Yii::t('app', 'The old password is not correct.'));
        }
    }

    /**
     * Generates and returns a random 10 character password.
     *
     * @since 0.0.1
     * @return string
     */
    public static function generatePassword()
    {
        return Yii::$app->security->generateRandomString(10);
    }

    /**
     * Generates random username
     */
    public function generateUsername()
    {
        $this->username = Yii::$app->security->generateRandomString(10);
    }

    /**
     * Get array of user status.
     *
     * @return array
     */
    public function getStatuses()
    {
        return [
            self::STATUS_ACTIVE => "Active",
            self::STATUS_INACTIVE => "Inactive",
            self::STATUS_DELETED => "Deleted",
        ];
    }

    /**
     * Get text from user status.
     *
     * @return string
     */
    public function getStatusText()
    {
        $status = $this->getStatuses();

        return isset($status[$this->status]) ? $status[$this->status] : "unknown($this->status)";
    }

    /**
     * Returns the fullname of the customer
     */
    public function getFullname()
    {
        return trim($this->first_name) . ' ' . trim($this->last_name);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddress()
    {
        return $this->hasOne(Address::className(), ['customer_id' => 'id'])->andOnCondition(['is_default' => 1]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddresses()
    {
        return $this->hasMany(Address::className(), ['customer_id' => 'id']);
    }

    /**
     * Generates profile_image from social auth and sets it to the model
     *
     * @param string $profile_image
     */
    public function setImage($profile_image)
    {
        $this->profile_image = $profile_image;
    }

    /**
     * @inheritdoc
     */
    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            return true;
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                //
            }

            return true;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function afterLogin($event)
    {
        $event->identity->updateAttributes(['logged_at' => new Expression('NOW()')]);
    }

    /**
     * Sends an email to user after registration
     *
     * @return boolean
     */
    public function sendRegistrationMail($event)
    {
        return Yii::$app->mailer->compose(
                ['html' => 'registration-html', 'text' => 'registration-text'],
                ['model' => $event->sender]
            )
            ->setTo($event->sender->email)
            ->setFrom([Yii::$app->params['noreplyEmail'] => Yii::$app->name])
            ->setSubject('Thanks for registering with us.')
            ->send();
    }
}
