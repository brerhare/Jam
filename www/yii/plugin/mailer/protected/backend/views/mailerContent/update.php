<?php
/* @var $this MailerContentController */
/* @var $model MailerContent */

$this->menu=array(
	array('label'=>'Manage Mail Content', 'url'=>array('admin')),
);
?>

<h2>Update Mail Content</h2>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
