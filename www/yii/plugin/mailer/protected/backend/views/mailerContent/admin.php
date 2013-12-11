<?php
$this->menu=array(
	array('label'=>'Create Mail Content','url'=>array('create')),
);

?>

<h2>Manage Mail Content</h2>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'mailer-content-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		//'id',
		//'uid',
		'title',
		'date',
		//'content',
		'sent',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>
