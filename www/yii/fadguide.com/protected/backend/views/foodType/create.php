<?php
$this->breadcrumbs=array(
	'Food Types'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List FoodType','url'=>array('index')),
	array('label'=>'Manage FoodType','url'=>array('admin')),
);
?>

<h1>Create FoodType</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>