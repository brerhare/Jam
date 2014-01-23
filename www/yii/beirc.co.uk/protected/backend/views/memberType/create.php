<?php

$this->menu=array(
	array('label'=>'Manage Member Types','url'=>array('admin')),
);
?>

<h2>Create MemberType</h2>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
