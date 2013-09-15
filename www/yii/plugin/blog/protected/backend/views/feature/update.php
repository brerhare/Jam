<?php

$this->menu=array(
	array('label'=>'Manage Features','url'=>array('admin')),
);
?>

<h2>Update Feature</h2>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
