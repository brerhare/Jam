<?php
/* @var $this GalleryController */
/* @var $model Gallery */

/*$this->breadcrumbs=array(
	'Galleries'=>array('index'),
	'Manage',
);*/

$this->menu=array(
//	array('label'=>'List Gallery', 'url'=>array('index')),
	array('label'=>'Create Gallery', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#gallery-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Galleries for
	<?php
	$model_product = Product::model()->findByPk(Yii::app()->session['product_id']);
	echo $model_product->name;
	echo '<p><p><b>Dont do anything on this page, its being worked on</b>'
	?>
</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'gallery-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'name'=>'id',
			'htmlOptions'=>array('width'=>'40px'),
		),
		'name',
		array(
			'name'=>'carousel',
			'header'=>'Home page carousel?',
			'filter'=>array('1'=>'Yes','0'=>'No'),
			'value'=>'($data->carousel=="1")?("Yes"):("No")'
		),
		array(
			'name'=>'filter',
			'header'=>'Site product page??',
			'filter'=>array('1'=>'Yes','0'=>'No'),
			'value'=>'($data->filter=="1")?("Yes"):("No")'
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update}{delete}'
		),
	),
)); ?>
