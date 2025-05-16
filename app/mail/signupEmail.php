<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $user app\models\User */
/* @var $password string */

?>

Здравствуйте!
<br><br>
Вы зарегистрированы на сайте: <?= Html::a(Html::encode(Yii::$app->name),Url::base(true)) ?>
<br><br>
Ваш логин: <?= Html::encode($user->email) ?>
<br>
Ваш пароль: <?= Html::encode($password) ?>
<br><br>
Если Вы не регистрировались на нашем сайте, то просто удалите это письмо.