<?php
/* @var $this MailerMemberController */
/* @var $model MailerMember */

$this->breadcrumbs=array(
	'Mailer Members'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List MailerMember', 'url'=>array('index')),
	array('label'=>'Create MailerMember', 'url'=>array('create')),
	array('label'=>'Update MailerMember', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete MailerMember', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage MailerMember', 'url'=>array('admin')),
);
?>

<h1>View MailerMember #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'uid',
		'email_address',
		'first_name',
		'last_name',
		'telephone',
		'address',
		'active',
		'mailer_list_id',
	),
)); ?>
