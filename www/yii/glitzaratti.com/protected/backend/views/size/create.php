<?php
/* @var $this SizeController */
/* @var $model Size */

/* $this->breadcrumbs=array(
	'Sizes'=>array('index'),
	'Create',
); */

$this->menu=array(
	/*array('label'=>'List Size', 'url'=>array('index')),*/
	array('label'=>'Manage Sizes', 'url'=>array('admin')),
);
?>

<h1>Create Size for
	<?php
	$model_category = Category::model()->findByPk(Yii::app()->session['category_id']);
	echo $model_category->name;
	?>
</h1>


<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>