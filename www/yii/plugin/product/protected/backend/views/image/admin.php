<?php
$this->menu=array(
	array('label'=>'Back to Manage Products', 'url'=>array('product/admin')),
	array('label'=>'Create Image','url'=>array('create')),
);

?>

<h2>Manage Images for
<?php
    $model_product = Product::model()->findByPk(Yii::app()->session['product_id']);
    echo $model_product->name;
?>  
</h2>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'image-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'uid',
		'filename',
		'product_product_id',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{update}{delete}',
		),
	),
)); ?>
