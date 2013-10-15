<?php
$this->breadcrumbs=array(
	'Ws'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Ws','url'=>array('index')),
	array('label'=>'Create Ws','url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('ws-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Ws</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button btn')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'ws-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'event_id',
		'os_grid_ref',
		'grade',
		'booking_essential',
		'min_age',
		/*
		'max_age',
		'child_ages_restrictions',
		'additional_venue_info',
		'full_price_notes',
		'short_description',
		'wheelchair_accessible',
		*/
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>
