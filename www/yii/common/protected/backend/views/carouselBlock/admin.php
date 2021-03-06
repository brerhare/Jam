<?php

$this->menu=array(
	array('label'=>'Create Carousel Content','url'=>array('create')),
);

?>

<h1>Manage Carousel Content</h1>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'carousel-block-grid',
	'dataProvider'=>$model->search(),
	// 'filter'=>$model,
	'columns'=>array(
		// 'id',
		'sequence',
        array(
            'name'  => 'title',
            'value' => 'CHtml::link($data->title, Yii::app()->createUrl("carouselBlock/update",array("id"=>$data->primaryKey)))',
            'type'  => 'raw',
        ),

		// 'active',
		// 'content',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{update}{delete}',
		),
	),
)); ?>
