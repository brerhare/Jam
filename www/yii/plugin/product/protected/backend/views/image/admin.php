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

<style>
img { height:50px;}
</style>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'image-grid',
	'dataProvider'=>$model->search(),
	//'filter'=>$model,
	'columns'=>array(
		//'id',
		//'uid',
		'filename',
        array('name'=>'filename',
            'type'=>'html',
            'header'=>'Picture',
            'value'=> 'CHtml::image("/product/userdata/image/" . $data->filename, "image", array("height"=>"50"))',
        ),
		//'product_product_id',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{update}{delete}',
		),
	),
)); ?>
