<?php
$this->menu=array(
	array('label'=>'List Departments','url'=>array('index')),
);
?>

<h2>Create Department</h2>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
