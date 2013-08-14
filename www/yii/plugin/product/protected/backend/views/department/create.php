<?php
$this->menu=array(
	array('label'=>'List Departments','url'=>array('index')),
);
?>

<h1>Create Department</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
