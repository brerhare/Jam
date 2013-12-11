<?php
/* @var $this MailerListController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Mailer Lists',
);

$this->menu=array(
	array('label'=>'Create MailerList', 'url'=>array('create')),
	array('label'=>'Manage MailerList', 'url'=>array('admin')),
);
?>

<h1>Mailer Lists</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
