<?php
/* @var $this MailerListController */
/* @var $model MailerList */

$this->breadcrumbs=array(
	'Mailer Lists'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List MailerList', 'url'=>array('index')),
	array('label'=>'Create MailerList', 'url'=>array('create')),
	array('label'=>'Update MailerList', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete MailerList', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage MailerList', 'url'=>array('admin')),
);
?>

<h1>View MailerList #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'uid',
		'name',
	),
)); ?>
