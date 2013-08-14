<?php

$this->menu=array(
	array('label'=>'Manage Vat Rates','url'=>array('admin')),
);
?>

<h1>Update Vat Rate</h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>