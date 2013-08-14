<?php
$this->menu=array(
	array('label'=>'Manage Departments','url'=>array('admin')),
);
?>

<h1>Update Department</h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
