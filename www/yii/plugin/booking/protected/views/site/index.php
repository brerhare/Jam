<?php

$this->menu=array(
        array('label'=>'Manage Rooms','url'=>array('admin')),
);
?>

<h1>Update Room <?php echo $model->title; ?></h1>

<?php echo $model->description; ?>
<?php echo $roomdata[2]; ?>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'roomdata'=>$roomdata)); ?>
