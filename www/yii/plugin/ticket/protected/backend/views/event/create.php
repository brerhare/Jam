<?php
/*$this->breadcrumbs=array(
	'Events'=>array('index'),
	'Create',
);*/

$this->menu=array(
	array('label'=>'Manage Events','url'=>array('admin')),
);
?>

<h2>Create Event</h2>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
