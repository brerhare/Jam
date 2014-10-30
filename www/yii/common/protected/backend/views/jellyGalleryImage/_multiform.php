<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'jelly-gallery-image-form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>


	<!-- Uploadify starts -->
    <link rel="stylesheet" type="text/css" href="/js/uploadify/uploadify.css">
    <script type="text/javascript" src="/js/uploadify/jquery.uploadify.js"></script>
    <script src="/js/uploadify/jquery.uploadify.min.js" type="text/javascript"></script>

    <script type="text/javascript">
    $(function() {
        $('#file_upload').uploadify({
            'swf'      : '/js/uploadify//uploadify.swf',
            'uploader' : '<?php echo $this->createUrl('jellyGalleryImage/multiCreateUpload');?>',
            // Your options here
        });
    });
    </script>

	<br/></br/>

	<input type="hidden" name="uploadify" value="uploadify">
	<input type="file" name="file_upload" id="file_upload" />

	<!-- Uploadify ends -->


	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>'Done',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
