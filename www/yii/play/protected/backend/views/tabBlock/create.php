<?php
$this->menu=array(
	array('label'=>'Manage Tab Content','url'=>array('admin')),
);
?>

<h1>Create Tab Content</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
