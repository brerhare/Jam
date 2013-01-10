<?php
/* @var $this ImageController */
/* @var $model Image */

/*$this->breadcrumbs=array(
	'Images'=>array('index'),
	'Manage',
);*/

$this->menu=array(
//	array('label'=>'List Image', 'url'=>array('index')),
	array('label'=>'Create Image', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#image-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Images for
<?php
    $model_product = Product::model()->findByPk(Yii::app()->session['product_id']);
    echo $model_product->name;
?>
</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'image-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
            'name'=>'id',
            'htmlOptions'=>array('width'=>'40px'),
        ),
		'filename',
/*		'product_id', */
        array('name'=>'filename',
            'type'=>'html',
            'header'=>'Picture',
            'value'=> 'CHtml::image("/userdata/image/" . $data->filename, "image", array("height"=>50))',
        ),
		array(
			'class'=>'CButtonColumn',
            'template'=>'{update}{delete}',
		),
	),
)); ?>
