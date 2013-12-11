<?php
$this->breadcrumbs=array(
	'Mailer Members',
);

$this->menu=array(
	array('label'=>'Create MailerMember','url'=>array('create')),
	array('label'=>'Manage MailerMember','url'=>array('admin')),
);
?>

<h1>Mailer Members</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
