<?php
/* @var $this ProductController */
/* @var $model Product */

$this->breadcrumbs=array(
	'Products'=>array('admin'),
	'Manage',
);

$this->menu=array(
//	array('label'=>'List Product', 'url'=>array('list')),
	array('label'=>'Create Product', 'url'=>array('create')),
);


Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#product-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Products</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'product-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'name',
		'price',
		'description',
/*
        'weight_kg',
        'pack_height_mm',
        'pack_width_mm',
        'pack_depth_mm',
*/

        array(
            'header'=>'Category',
            'name'=>'category.name',
//          'value'=>'',
        ),



/*      'category_id', */
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
