<?php
$this->breadcrumbs=array(
	'Mailer Contents',
);

$this->menu=array(
	array('label'=>'Create MailerContent','url'=>array('create')),
	array('label'=>'Manage MailerContent','url'=>array('admin')),
);
?>

<h1>Mailer Contents</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
