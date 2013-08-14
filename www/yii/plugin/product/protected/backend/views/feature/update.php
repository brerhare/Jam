<?php

$this->menu=array(
	array('label'=>'Manage Features','url'=>array('admin')),
);
?>

<h1>Update Feature</h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
