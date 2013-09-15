<?php
$this->menu=array(
	array('label'=>'Manage Features','url'=>array('admin')),
);
?>

<h2>Create Feature</h2>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
