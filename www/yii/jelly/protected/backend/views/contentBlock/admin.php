<?php

$this->menu=array(
	array('label'=>'Create Page Content','url'=>array('create')),
);

?>

<h1>Manage Page Content</h1>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'content-block-grid',
	'dataProvider'=>$model->search(),
	// 'filter'=>$model,
	'columns'=>array(
		// 'id',
		//'sequence',
		'title',
		'url',
		'active:boolean',
		// 'content',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{update}{delete}',
		),
	),
)); ?>
