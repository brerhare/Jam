<?php
/*
$this->breadcrumbs=array(
	'Ticket Types'=>array('index'),
	'Manage',
);
*/

$this->menu=array(
	array('label'=>'Back to Manage Events', 'url'=>array('event/admin')),
	array('label'=>'Create Ticket Type','url'=>array('create')),
);

/*
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('ticket-type-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
*/
?>

<h2>Manage Ticket Types for
<?php
    $model_event = Event::model()->findByPk(Yii::app()->session['event_id']);
    echo $model_event->title; 
?> 
</h2>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'ticket-type-grid',
	'dataProvider'=>$model->search(),
	//'filter'=>$model,
	'columns'=>array(
		//'id',
		//'uid',
		'description',
		'price',
		'places_per_ticket',
		'max_tickets_per_order',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>
