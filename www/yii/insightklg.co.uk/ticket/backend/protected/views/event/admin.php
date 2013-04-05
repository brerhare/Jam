<?php
/* @var $this EventController */
/* @var $model Event */

/*
$this->breadcrumbs=array(
	'Events'=>array('index'),
	'Manage',
);*/

$this->menu=array(
/*	array('label'=>'List Event', 'url'=>array('index')),*/
	array('label'=>'Create Event', 'url'=>array('create')),
);

/*
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('event-grid', {
		data: $(this).serialize()
	});
	return false;
});
");*/
?>

<h3>Manage Events</h3>


<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'event-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'title',
		'start_date',
		'address',
		'post_code',
		'web_link',
		/*
		'max_tickets',
		'ticket_text',
		'ticket_logo_path',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
