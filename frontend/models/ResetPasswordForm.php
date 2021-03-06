<?php
namespace frontend\models;

use common\models\User;
use yii\base\InvalidParamException;
use yii\base\Model;
use Yii;

/**
 * Password reset form
 */
class ResetPasswordForm extends Model
{
    public $password;
    public $repeatPassword;

    /**
     * @var \common\models\User
     */
    private $_user;


    /**
     * Creates a form model given a token.
     *
     * @param  string                          $token
     * @param  array                           $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct($token, $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidParamException(Yii::t('app', 'Password reset token cannot be blank.'));
        }
        $this->_user = User::findByPasswordResetToken($token);
        if (!$this->_user) {
            throw new InvalidParamException(Yii::t('app', 'Wrong password reset token.'));
        }
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['password', 'required', 'message'=>Yii::t('app', 'Необходимо заполнить это поле')],
            ['password', 'string', 'length' => [6, 255], 'message'=>Yii::t('app', 'Это поле должно содержать минимум 6 символов')],

            ['repeatPassword', 'required', 'message'=>Yii::t('app', 'Необходимо заполнить это поле')],
            ['repeatPassword', 'string', 'length' => [6, 255], 'message'=>Yii::t('app', 'Это поле должно содержать минимум 6 символов')],
            ['repeatPassword', 'compare', 'compareAttribute'=>'password', 'skipOnEmpty' => false, 'message'=>Yii::t('app', 'Пароли не совпадают')],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'password' => Yii::t('app', 'Пароль'),
            'repeatPassword' => Yii::t('app', 'Подтверждение')
        ];
    }

    /**
     * Resets password.
     *
     * @return boolean if password was reset.
     */
    public function resetPassword()
    {
        $user = $this->_user;
        $user->setPassword($this->password);
        $user->removePasswordResetToken();

        return $user->save();
    }
}
