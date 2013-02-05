<?php
/* @var $this SizeController */
/* @var $model Size */

/* $this->breadcrumbs=array(
	'Sizes'=>array('index'),
	'Manage',
); */

$this->menu=array(
	array('label'=>'Manage Categories', 'url'=>array('category/admin')),
	array('label'=>'Create Size', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#size-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Sizes for
	<?php
	$model_category = Category::model()->findByPk(Yii::app()->session['category_id']);
	echo $model_category->name;
	?>
</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'size-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		/*'id',*/
		'text',
		/*'is_a_default',*/
		/*'category_id',*/
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update}{delete}',
		),
	),
)); ?>
