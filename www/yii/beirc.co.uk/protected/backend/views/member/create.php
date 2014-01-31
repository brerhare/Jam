<?php

$this->menu=array(
	array('label'=>'Manage Members','url'=>array('admin')),
);
?>

<h2>Create Member</h2>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
