<?php
$this->breadcrumbs=array(
	'Jelly Adblocks'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List JellyAdblock','url'=>array('index')),
	array('label'=>'Manage JellyAdblock','url'=>array('admin')),
);
?>

<h1>Create JellyAdblock</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>