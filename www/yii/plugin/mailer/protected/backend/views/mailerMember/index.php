<?php
/* @var $this MailerMemberController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Mailer Members',
);

$this->menu=array(
	array('label'=>'Create MailerMember', 'url'=>array('create')),
	array('label'=>'Manage MailerMember', 'url'=>array('admin')),
);
?>

<h1>Mailer Members</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
