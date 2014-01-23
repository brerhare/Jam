<?php

$this->menu=array(
	array('label'=>'Create Member','url'=>array('create')),
);

?>

<h2>Manage Members</h2>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'member-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		//'id',
		'username',
		'password',
		'email',
		'member_type_id',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>
