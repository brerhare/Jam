<?php
/* @var $this MailerMemberController */
/* @var $model MailerMember */

$this->menu=array(
	array('label'=>'Manage List Members', 'url'=>array('admin')),
);
?>

<h2>Update Mailing List Member</h2>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
