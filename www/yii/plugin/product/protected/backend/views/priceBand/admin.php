<?php
$this->menu=array(
	array('label'=>'Create Price Band','url'=>array('create')),
);
?>

<h2>Manage Price Bands</h2>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'price-band-grid',
	'dataProvider'=>$model->search(),
	//'filter'=>$model,
	'columns'=>array(
		//'id',
		//'uid',
		//'max',

        array(
            'name'  => 'max',
            'value' => 'CHtml::link($data->max, Yii::app()->createUrl("priceBand/update",array("id"=>$data->primaryKey)))',
            'type'  => 'raw',
        ),

		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{update}{delete}'),
	),
)); ?>
