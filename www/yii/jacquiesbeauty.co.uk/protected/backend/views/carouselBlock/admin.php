<?php

$this->menu=array(
	array('label'=>'Create Carousel Block','url'=>array('create')),
);

?>

<h1>Manage Carousel Blocks</h1>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'carousel-block-grid',
	'dataProvider'=>$model->search(),
	// 'filter'=>$model,
	'columns'=>array(
		// 'id',
		'sequence',
		'title',
		// 'active',
		// 'content',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{update}{delete}',
		),
	),
)); ?>
