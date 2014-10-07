<?php
$this->breadcrumbs=array(
	'Jelly Settings'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List JellySetting','url'=>array('index')),
	array('label'=>'Manage JellySetting','url'=>array('admin')),
);
?>

<h1>Create JellySetting</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>