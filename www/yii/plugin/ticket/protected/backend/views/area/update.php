<?php
/*
$this->breadcrumbs=array(
	'Areas'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);
*/

$this->menu=array(
//	array('label'=>'List Area','url'=>array('index')),
	array('label'=>'Manage Areas','url'=>array('admin')),
);

?>

<h2>Update Area <?php echo $model->description; ?></h2>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
