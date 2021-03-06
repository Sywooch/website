<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "z_user".
 *
 * @see: @common\models\user\User for basic methods
 */
class User extends \common\models\User
{

  /* Relations - Frontend */
  public function getNews()
  {
      return $this->hasMany(News::className(), ['user' => 'id']);
  }

}
