<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/login']);
?>

Hello <?= $username?>,

<br>Your Username/Email ID: <?= $model->email ?>
<br>Your Password: <?= $model->password ?>

<br>After login, Please Kindly Delete this Message for security.

<br>
Thanks, 
<br>
<?= $resetLink ?>

