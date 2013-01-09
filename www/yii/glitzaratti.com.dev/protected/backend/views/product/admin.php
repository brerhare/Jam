<?php
/* @var $this ProductController */
/* @var $model Product */

/*$this->breadcrumbs=array(
	'Products'=>array('admin'),
	'Manage',
);*/

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
        array(
            'name'=>'id',
            'htmlOptions'=>array('width'=>'40px'),
        ),
		'name',
		'description',
        array(
            'name'=>'price',
            'htmlOptions'=>array('width'=>'60px'),
        ),
/*
        'weight_kg',
        'pack_height_mm',
        'pack_width_mm',
        'pack_depth_mm',
*/
        array(
            'header'=>'Size Category',
            'name'=>'category.name',
//          'value'=>'',
            'htmlOptions'=>array('width'=>'80px'),
        ),
/*      'category_id', */
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update}{images}{delete}',
			'buttons'=>array(
				'images' => array(
					'label'=>'Images',
					'imageUrl'=>Yii::app()->request->baseUrl.'/img/image.png',
					'url'=>'Yii::app()->controller->createUrl("image/admin", array("product_id"=>$data->primaryKey))',
				),
			)

		),
	),
)); ?>
