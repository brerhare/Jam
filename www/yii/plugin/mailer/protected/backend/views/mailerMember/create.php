<?php

$this->menu=array(
	array('label'=>'Manage List Members','url'=>array('admin')),
);
?>

<h2>Create List Member</h2>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
