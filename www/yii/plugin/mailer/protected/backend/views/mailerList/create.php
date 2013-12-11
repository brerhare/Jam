<?php
/* @var $this MailerListController */
/* @var $model MailerList */

$this->menu=array(
	array('label'=>'Manage Mailing Lists', 'url'=>array('admin')),
);
?>

<h2>Create Mailing List</h2>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
