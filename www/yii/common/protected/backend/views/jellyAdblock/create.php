<?php

$this->menu=array(
	array('label'=>'Manage Ad blocks','url'=>array('admin')),
);
?>

<h2>Create Ad block</h2>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
