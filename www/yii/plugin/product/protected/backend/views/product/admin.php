<?php
$this->menu=array(
	array('label'=>'Back to Manage Departments', 'url'=>array('department/admin')),
	array('label'=>'Create Product','url'=>array('create')),
);

?>

<h2>Manage Products for
<?php
    $model_department = Department::model()->findByPk(Yii::app()->session['department_id']);
    echo $model_department->name;
?>
</h2>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	//'id'=>'product-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		//'id',
		//'uid',
        array(
            'name'  => 'name',
            'value' => 'CHtml::link($data->name, Yii::app()->createUrl("product/update",array("id"=>$data->primaryKey)))',
            'type'  => 'raw',
        ),
        array(
            'name'  => 'description',
            'value' => 'CHtml::link($data->description, Yii::app()->createUrl("product/update",array("id"=>$data->primaryKey)))',
            'type'  => 'raw',
        ),

		/*
		'weight',
		'height',
		'width',
		'depth',
		'volume',
		'product_department_id',
		'product_vat_id',
		*/
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{update}{images}{delete}',
            'buttons'=>array(
                'images' => array(
                    'label'=>'Images',
                    'imageUrl'=>Yii::app()->request->baseUrl.'/img/image.png',
                    'url'=>'Yii::app()->controller->createUrl("image/session", array("product_id"=>$data->primaryKey))',
                ),
			),
		),
	),
)); ?>
