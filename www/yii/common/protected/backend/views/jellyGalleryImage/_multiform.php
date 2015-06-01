<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'jelly-gallery-image-form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>


	<!-- Uploadifive starts -->
    <link rel="stylesheet" type="text/css" href="/js/uploadifive/uploadifive.css">
    <script type="text/javascript" src="/js/uploadifive/jquery.uploadifive.js"></script>

    <script type="text/javascript">
        $(function() {
            $('#file_upload').uploadifive({
                'uploadScript' : '<?php echo $this->createUrl('jellyGalleryImage/multiCreateUpload');?>',
            });
        });
    </script>

	<br/></br/>

	<input type="hidden" name="uploadifive" value="uploadifive">
	<input id="file_upload" name="file_upload" type="file" multiple="true">

	<!-- Uploadifive ends -->


	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>'Done',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
