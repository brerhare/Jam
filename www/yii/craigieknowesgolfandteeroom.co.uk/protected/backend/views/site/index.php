<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<h1>Welcome to <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>

<br/>

<?php if (!Yii::app()->user->isGuest)
echo '<iframe src="http://craigieknowesgolfandteeroom.co.uk:rh63jdu@www.craigieknowesgolfandteeroom.co.uk/awstats/awstats.pl?framename=mainright" height=5500px width=100%></iframe>';
?>

