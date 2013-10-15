<?php

$this->menu=array(
	array('label'=>'Manage Events','url'=>array('admin')),
);
?>

<h1>Create Event</h1>
<?php echo $this->renderPartial('_form', array('model'=>$model, 'model2'=>$model2)); ?>