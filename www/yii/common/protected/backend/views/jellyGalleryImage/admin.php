<?php

$this->menu=array(
	array('label'=>'Back to Manage Albums', 'url'=>array('jellyGallery/admin')),
	array('label'=>'Create Image','url'=>array('create')),
	array('label'=>'Upload Multiple Images','url'=>array('multiCreate')),
);

?>

<style>
img { height:50px;}
</style>

<h2>Manage Images for
<?php
    $model_gallery = JellyGallery::model()->findByPk(Yii::app()->session['gallery_id']);
    echo $model_gallery->title;
?>
</h2>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'jelly-gallery-image-grid',
	'dataProvider'=>$model->search(),
	//'filter'=>$model,
	'columns'=>array(
		//'id',

        array(
            'name'  => 'sequence',
            'value' => 'CHtml::link($data->sequence, Yii::app()->createUrl("jellyGalleryImage/update",array("id"=>$data->primaryKey)))',
            'type'  => 'raw',
        ),

		//'text',

        array('name'=>'image',
            'type'=>'html',
            'header'=>'Picture',
            'value'=> 'CHtml::image("/userdata/jelly/gallery/" . $data->image, "image", array("height"=>"50"))',
        ),

		//'image',
		//'url',
		//'jelly_gallery_id',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{update}{delete}',
		),
	),
)); ?>
