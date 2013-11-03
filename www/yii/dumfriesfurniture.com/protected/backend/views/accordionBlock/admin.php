<?php
$this->menu=array(
	array('label'=>'Create Accordion Content','url'=>array('create')),
);

?>

<h1>Manage Accordion Content</h1>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'accordion-block-grid',
	'dataProvider'=>$model->search(),
	//'filter'=>$model,
	'columns'=>array(
		//'id',

		array(
			'name'  => 'title',
			'value' => 'CHtml::link($data->title, Yii::app()->createUrl("accordionBlock/update",array("id"=>$data->primaryKey)))',
			'type'  => 'raw',
		),

		'sequence',
		'url',
		'content',
		'image',

		array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'template'=>'{update}{delete}',
            
        ),

	),
)); ?>
