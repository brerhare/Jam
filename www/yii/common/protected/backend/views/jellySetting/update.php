<?php

/*****
$this->menu=array(
	array('label'=>'List JellySetting','url'=>array('index')),
	array('label'=>'Create JellySetting','url'=>array('create')),
	array('label'=>'View JellySetting','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage JellySetting','url'=>array('admin')),
);
*****/
?>

<h2>Update Settings</h2>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
