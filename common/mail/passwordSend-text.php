<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/login']);
?>

Hello <?= $username?>,

<?= $message ?>

Thanks, 
<br>
<?= $resetLink ?>

