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

<h1>Manage Images</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'image-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'filename',
		'product_id',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
