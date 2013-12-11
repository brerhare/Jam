<?php
/* @var $this MailerListController */
/* @var $model MailerList */

$this->menu=array(
	array('label'=>'Create Mailing List', 'url'=>array('create')),
);

?>

<h2>Manage Mailing Lists</h2>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'mailer-list-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		//'id',
		//'uid',
		'name',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
