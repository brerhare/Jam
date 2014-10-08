<?php

$this->menu=array(
	array('label'=>'Manage Ticker','url'=>array('admin')),
);
?>

<h2>Create Ticker</h2>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
