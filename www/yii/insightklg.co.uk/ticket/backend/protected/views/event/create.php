<?php
/* @var $this EventController */
/* @var $model Event */

/*
$this->breadcrumbs=array(
	'Events'=>array('index'),
	'Create',
);*/

/*
$this->menu=array(
	array('label'=>'List Event', 'url'=>array('index')),
	array('label'=>'Manage Event', 'url'=>array('admin')),
);*/
?>

<h3>Create Event</h3>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>