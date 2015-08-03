<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<h1>Welcome to <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>

<br/>

<?php if (!Yii::app()->user->isGuest)
echo '<iframe src="http://zoelifecoaching.co.uk:u5hc8u5@www.zoelifecoaching.co.uk/awstats/awstats.pl?framename=mainright" height=5500px width=100%></iframe>';
?>

