<?php
/* @var $this CategoryController */
/* @var $model Category */

/*$this->breadcrumbs=array(
	'Categories'=>array('admin'),
	'Create',
);*/

$this->menu=array(
//	array('label'=>'List Category', 'url'=>array('index')),
	array('label'=>'Manage Categories', 'url'=>array('admin')),
);
?>

<h1>Create Category</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
