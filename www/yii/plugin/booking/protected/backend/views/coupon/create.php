<?php

$this->menu=array(
	array('label'=>'Manage Coupons','url'=>array('admin')),
);
?>

<h1>Create Coupon</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>