<?php
$this->menu=array(
	array('label'=>'Create Image','url'=>array('create')),
);

?>

<h2>Manage Images</h2>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'image-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'uid',
		'filename',
		'product_product_id',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{update}{delete}',
		),
	),
)); ?>
