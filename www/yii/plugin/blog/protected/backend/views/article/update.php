<?php

$this->menu=array(
	array('label'=>'Manage Articles','url'=>array('admin')),
);
?>

<h2>Update Article</h2>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
