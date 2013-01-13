<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<?php if (Yii::app()->user->isGuest): ?>
  <h1>Not logged in</h1>
  <p>
  <p>Please login to access maintenance functions
<?php else: ?>
  <h1>You are logged in</h1>
<?php endif ?>

