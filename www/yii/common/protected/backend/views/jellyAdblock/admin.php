<?php

$this->menu=array(
	array('label'=>'Create Ad block','url'=>array('create')),
);

?>

<style>
img { height:50px;}
</style>

<h2>Manage Ad Blocks</h2>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'jelly-adblock-grid',
	'dataProvider'=>$model->search(),
	//'filter'=>$model,
	'columns'=>array(
		//'id',

        array('name'=>'image',
            'type'=>'html',
            'header'=>'Picture',
            'value'=> 'CHtml::image("/userdata/jelly/adblock/" . $data->image, "image", array("height"=>"50"))',
        ),

		'url',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>
