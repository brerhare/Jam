<?php

$this->menu=array(
	array('label'=>'Create Slider Content','url'=>array('create')),
);

?>

<h1>Manage Slider Content</h1>

<style>
img { height:50px;}
</style>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'jelly-slider-image-grid',
	'dataProvider'=>$model->search(),
	// 'filter'=>$model,
	'columns'=>array(
		// 'id',
		'sequence',

        array('name'=>'image',
            'type'=>'html',
            'header'=>'Picture',
            'value'=> 'CHtml::image("/userdata/jelly/sliderimage/" . $data->image, "image", array("height"=>"50"))',
        ),

        array(
            'name'  => 'title',
            'value' => 'CHtml::link($data->title, Yii::app()->createUrl("jellySliderImage/update",array("id"=>$data->primaryKey)))',
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
