<?php
$this->breadcrumbs=array(
	'Plugins'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'Create Plugin','url'=>array('create')),
);
?>

<h1>Manage Plugins</h1>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button btn')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'plugin-grid',
	'dataProvider'=>$model->search(),
	// 'filter'=>$model,
	'columns'=>array(
		'id',
		'description',
		'container_url',
		'container_width',
		'container_height',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>
