<?php
$this->menu=array(
	array('label'=>'Manage Price Bands','url'=>array('admin')),
);
?>

<h2>Create Price Band</h2>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>