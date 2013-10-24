<?php
$this->menu=array(
	array('label'=>'Create Preset','url'=>array('create')),
);
?>

<h2>Manage Presets</h2>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'filter-grid',
	'dataProvider'=>$model->search(),
	//'filter'=>$model,
	'columns'=>array(
		//'id',
		//'uid',
		//'text',

        array(
            'name'  => 'text',
            'value' => 'CHtml::link($data->text, Yii::app()->createUrl("filter/update",array("id"=>$data->primaryKey)))',
            'type'  => 'raw',
        ),

		'url',

		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{update}{delete}'),

	),
)); ?>
