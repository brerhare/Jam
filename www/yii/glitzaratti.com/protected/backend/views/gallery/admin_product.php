<?php
/* @var $this GalleryController */
/* @var $model Gallery */

/*$this->breadcrumbs=array(
	'Galleries'=>array('index'),
	'Manage',
);*/

$this->menu=array(
//	array('label'=>'List Gallery', 'url'=>array('index')),
	array('label'=>'Manage Products', 'url'=>array('product/admin')),
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
	?>
</h1>

<?php echo CHtml::beginForm(); ?>

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
			'class' => 'CCheckBoxColumn',
			'id'=>'galleryCheckBoxes',
			'checked'=>function($data, $row) use ($garray){ /* nb has to be an anonymous func */
				return in_array($data->id, $garray);},      /* garray is a list of attached galleries from controller */
			'selectableRows' => '20',
			'header'=>'Selected',
		),
	),
)); ?>

<?php echo CHtml::submitButton('Save', array('name' => 'SaveButton')); ?>

<?php echo CHtml::endForm(); ?>