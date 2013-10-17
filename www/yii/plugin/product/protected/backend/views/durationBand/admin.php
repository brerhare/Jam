<?php
$this->menu=array(
	array('label'=>'Create Duration','url'=>array('create')),
);
?>

<h2>Manage Durations</h2>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'duration-band-grid',
	'dataProvider'=>$model->search(),
	//'filter'=>$model,
	'columns'=>array(
		//'id',
		//'uid',
		//'max',

        array(
            'name'  => 'max',
            'value' => 'CHtml::link($data->max, Yii::app()->createUrl("durationBand/update",array("id"=>$data->primaryKey)))',
            'type'  => 'raw',
        ),

		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{update}{delete}'),
	),


)); ?>
