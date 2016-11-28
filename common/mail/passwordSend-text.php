<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/login']);
?>

Hello <?= $user->email ?>,

<br>Your Username/Email ID: <?= $user->email ?>
<br>Your Password: <?= $password ?>

<br>After login, Please Kindly Delete this Message for security.

<br>
Thanks, 
<br>
<?= $resetLink ?>

