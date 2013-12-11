<?php

$this->menu=array(
	array('label'=>'Create list Member','url'=>array('create')),
);

?>

<h2>Manage List Members</h2>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'mailer-member-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		//'id',
		//'uid',
		'email_address',
		'first_name',
		'last_name',
		//'telephone',
		//'address',
		//'active',
		//'mailer_list_id',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>
