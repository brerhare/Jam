<?php
$this->breadcrumbs=array(
	'Food Types'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List FoodType','url'=>array('index')),
	array('label'=>'Create FoodType','url'=>array('create')),
	array('label'=>'View FoodType','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage FoodType','url'=>array('admin')),
);
?>

<h1>Update FoodType <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>