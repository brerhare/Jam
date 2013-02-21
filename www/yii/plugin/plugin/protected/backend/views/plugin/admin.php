<?php
/* @var $this PluginController */
/* @var $model Plugin */

/*
$this->breadcrumbs=array(
	'Plugins'=>array('index'),
	'Manage',
); */

$this->menu=array(
	/* array('label'=>'List Plugin', 'url'=>array('index')), */
	array('label'=>'Create Plugin', 'url'=>array('create')),
);

/*
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#plugin-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
"); */
?>

<h1>Manage Plugins</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'plugin-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'description',
		'container_code',
		'container_width',
		'container_height',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
