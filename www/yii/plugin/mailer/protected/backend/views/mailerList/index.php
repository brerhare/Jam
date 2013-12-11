<?php
$this->breadcrumbs=array(
	'Mailer Lists',
);

$this->menu=array(
	array('label'=>'Create MailerList','url'=>array('create')),
	array('label'=>'Manage MailerList','url'=>array('admin')),
);
?>

<h1>Mailer Lists</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
