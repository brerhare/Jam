<?php
$this->menu=array(
	array('label'=>'Manage Departments','url'=>array('admin')),
);
?>

<h2>Create Department</h2>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
