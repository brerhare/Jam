<?php

$this->menu=array(
	//array('label'=>'List Program','url'=>array('index')),
	array('label'=>'Create Program','url'=>array('create')),
);

?>

<h1>Manage Programs</h1>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'program-grid',
	'dataProvider'=>$model->search(),
	//'filter'=>$model,
	'columns'=>array(
		//'id',
		'name',
		//'thumb_path',
		//'icon_path',
		//'event_program_fields_id',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>
