<?php
/* @var $this ProductController */
/* @var $model Product */

/*$this->breadcrumbs=array(
	'Products'=>array('admin'),
	'Create',
);*/

$this->menu=array(
//	array('label'=>'List Product', 'url'=>array('index')),
	array('label'=>'Manage Products', 'url'=>array('admin')),
);
?>

<h1>Create Product</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
