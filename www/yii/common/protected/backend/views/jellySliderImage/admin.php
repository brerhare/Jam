<?php

$this->menu=array(
	array('label'=>'Create Slider Content','url'=>array('create')),
);

?>

<h1>Manage Slider Content</h1>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'jelly-slider-html-grid',
	'dataProvider'=>$model->search(),
	// 'filter'=>$model,
	'columns'=>array(
		// 'id',
		'sequence',
        array(
            'name'  => 'title',
            'value' => 'CHtml::link($data->title, Yii::app()->createUrl("jellySliderHtml/update",array("id"=>$data->primaryKey)))',
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
