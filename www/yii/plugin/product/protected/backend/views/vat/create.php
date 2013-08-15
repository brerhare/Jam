<?php
$this->menu=array(
	array('label'=>'Manage Vat Rates','url'=>array('admin')),
);
?>

<h2>Create Vat Rate</h2>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
