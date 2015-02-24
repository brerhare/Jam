<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<h2>Welcome to <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h2>

<br/>

<?php if (!Yii::app()->user->isGuest)
echo '<iframe src="http://weetarget.co.uk:ghwu73c9e@www.weetarget.co.uk/awstats/awstats.pl?framename=mainright" height=5500px width=100%></iframe>';
?>

