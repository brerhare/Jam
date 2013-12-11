<?php
/* @var $this MailerContentController */
/* @var $model MailerContent */

$this->breadcrumbs=array(
	'Mailer Contents'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List MailerContent', 'url'=>array('index')),
	array('label'=>'Create MailerContent', 'url'=>array('create')),
	array('label'=>'Update MailerContent', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete MailerContent', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage MailerContent', 'url'=>array('admin')),
);
?>

<h1>View MailerContent #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'uid',
		'title',
		'date',
		'content',
		'sent',
	),
)); ?>
