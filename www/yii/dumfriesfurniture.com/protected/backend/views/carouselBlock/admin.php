<?php

$this->menu=array(
	array('label'=>'Create Slider Content','url'=>array('create')),
);

?>

<h1>Manage Slider Content</h1>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'carousel-block-grid',
	'dataProvider'=>$model->search(),
	// 'filter'=>$model,
	'columns'=>array(
		// 'id',

		array(
			'name'  => 'title',
			'value' => 'CHtml::link($data->title, Yii::app()->createUrl("carouselBlock/update",array("id"=>$data->primaryKey)))',
			'type'  => 'raw',
		),

		'sequence',

		// 'active',
		// 'content',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{update}{delete}',
		),
	),
)); ?>
