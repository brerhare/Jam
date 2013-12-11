<?php

$this->menu=array(
	array('label'=>'Create Mailing List','url'=>array('create')),
);

?>

<h2>Manage Mailing Lists</h2>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'mailer-list-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		//'id',
		//'uid',
		'name',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>
