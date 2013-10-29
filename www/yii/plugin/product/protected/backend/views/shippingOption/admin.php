<?php
$this->menu=array(
	array('label'=>'Create Shipping Options','url'=>array('create')),
);

?>

<h2>Manage Shipping Options</h2>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'shipping-option-grid',
	'dataProvider'=>$model->search(),
	//'filter'=>$model,
	'columns'=>array(
		//'id',
		//'uid',
		//'description',
        array(
            'name'  => 'description',
            'value' => 'CHtml::link($data->description, Yii::app()->createUrl("shippingOption/update",array("id"=>$data->primaryKey)))',
            'type'  => 'raw',
        ),
		'price',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{update}{delete}',
		),
	),
)); ?>
