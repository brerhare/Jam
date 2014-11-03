<?php
/*$this->breadcrumbs=array(
	'Events'=>array('index'),
	'Create',
);*/

$this->menu=array(
	array('label'=>'Manage Events','url'=>array('admin')),
);
?>

<h4>Create Event</h4>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
