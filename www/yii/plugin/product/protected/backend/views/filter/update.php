<?php
$this->menu=array(
	array('label'=>'Manage Presets','url'=>array('admin')),
);
?>

<h2>Update Preset</h2>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>