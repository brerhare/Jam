<?php

$this->menu=array(
	array('label'=>'Create Content Block','url'=>array('create')),
);

?>

<h1>Manage Content Blocks</h1>

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
