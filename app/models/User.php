<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $email
 * @property string $role
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 * @property string|null $auth_key
 * @property string|null $email_confirm_token
 * @property string $password_hash
 * @property string|null $password_reset_token
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_ACTIVE = 'active';
    const STATUS_WAIT = 'wait';
    const STATUS_BLOCKED = 'blocked';

    const ROLE_ADMIN = 'admin';
    const ROLE_MANAGER = 'manager';
    const ROLE_CLIENT = 'client';
    const ROLE_PARTNER = 'partner';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['auth_key', 'email_confirm_token', 'password_reset_token'], 'default', 'value' => null],
            [['email', 'role', 'status', 'created_at', 'updated_at', 'password_hash'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['email', 'role', 'status', 'email_confirm_token', 'password_hash', 'password_reset_token'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'email' => Yii::t('app', 'Email'),
            'role' => Yii::t('app', 'Role'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'email_confirm_token' => Yii::t('app', 'Email Confirm Token'),
            'password_hash' => Yii::t('app', 'Password Hash'),
            'password_reset_token' => Yii::t('app', 'Password Reset Token'),
        ];
    }

    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('findIdentityByAccessToken is not implemented.');
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function generateEmailConfirmToken()
    {
        $this->email_confirm_token = Yii::$app->security->generateRandomString();
    }

    public static function findByEmailConfirmToken($emailConfirmToken)
    {
        return static::findOne(['email_confirm_token' => $emailConfirmToken, 'status' => self::STATUS_WAIT]);
    }

    public function removeEmailConfirmToken()
    {
        $this->email_confirm_token = null;
    }

    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['passwordTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int)end($parts);

        return $timestamp + $expire >= time();
    }

    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function isAdmin()
    {
        return $this->role === static::ROLE_ADMIN;
    }
    public function isManager()
    {
        return $this->role === static::ROLE_MANAGER;
    }
    public function isClient()
    {
        return $this->role === static::ROLE_CLIENT;
    }
    public function isPartner()
    {
        return $this->role === static::ROLE_PARTNER;
    }

    public function isActive()
    {
        return $this->status === static::STATUS_ACTIVE;
    }

    public function isWait()
    {
        return $this->status === static::STATUS_WAIT;
    }

    public function isBlocked()
    {
        return $this->status === static::STATUS_BLOCKED;
    }

    public static function getStatuses()
    {
        return [
            self::STATUS_ACTIVE => 'Активен',
            self::STATUS_BLOCKED => 'Заблокирован',
            self::STATUS_WAIT => 'Ожидает подтверждения',
        ];
    }

    public static function getStatusName($status)
    {
        $statuses = self::getStatuses();

        return isset($statuses[$status]) ? $statuses[$status] : '';
    }

    public static function getRoles()
    {
        return [
            self::ROLE_PARTNER => 'Партнер',
            self::ROLE_ADMIN => 'Админ',
            self::ROLE_MANAGER => 'Менеджер',
            self::ROLE_CLIENT => 'Клиент',
        ];
    }

    public static function getRoleName($role)
    {
        $roles = self::getRoles();

        return isset($roles[$role]) ? $roles[$role] : '';
    }
}
