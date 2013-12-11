<?php
/* @var $this MailerMemberController */
/* @var $model MailerMember */

$this->menu=array(
	array('label'=>'Manage Members', 'url'=>array('admin')),
);
?>

<h2>Create Mailing List Members</h2>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
