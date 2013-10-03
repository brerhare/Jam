<?php

$this->menu=array(
	array('label'=>'Create Tab Content','url'=>array('create')),
);

?>

<h1>Manage Tab Content</h1>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'tab-block-grid',
	'dataProvider'=>$model->search(),
	// 'filter'=>$model,
	'columns'=>array(
		//'id',
		'sequence',
        array(
            'name'  => 'title',
            'value' => 'CHtml::link($data->title, Yii::app()->createUrl("tabBlock/update",array("id"=>$data->primaryKey)))',
            'type'  => 'raw',
        ),

		//'content',
		//'image',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{update}{delete}',
		),
	),
)); ?>
