<!-- Display a list of events to pick from -->

<style>
#page {
	border:0;
}
</style>

<div class="row">
    <div class="span6 well">
        <h3>Available Events</h3> 
        <table>
        <?php
            $criteria = new CDbCriteria;
            $criteria->addCondition("active = " . 1);
            $criteria->addCondition("uid = " . Yii::app()->session['uid']);
            $events = Event::model()->findAll($criteria);
            foreach ($events as $event):
            
            if (strlen($event->ticket_logo_path) > 0)
				$logo = Yii::app()->baseUrl . '/userdata/' . Yii::app()->session['uid'] . '/' . $event->ticket_logo_path;
			else
				$logo = Yii::app()->baseUrl . '/img/default_logo.jpg';
	
        ?>
        <tr>
        <td width="30%">
			<?php
			$imgdim = getimagesize(Yii::app()->basePath . "/../../" . $logo);
			$imgw=$imgdim[0];
			$imgh=$imgdim[1];
			$imgstr="width:120px";
			if ($imgh>120)
				$imgstr="height:120px";
			echo CHtml::image(
				$logo,
				'Event Image',
				array('style'=>$imgstr));
			?>
        </td>
        <td width="60%">
        <b><?php echo $event->title; ?></b>
        <br>
        <i><?php echo $event->date; ?></i>
        </td>
        <td width="10%">
        	<?php
        	$ref="none";
        	if ((isset($_GET['ref'])) && ($_GET['ref'] == 'bktji5308'))
        		$ref=$_GET['ref'];
        	?>
			<?php $this->widget('bootstrap.widgets.TbButton',array(
				'label' => 'Book',
				'url' => $this->createUrl('ticket/book', array('id'=>$event->id, 'sid'=>Yii::app()->session['sid'], 'ref'=>$ref)),
				'type' => 'primary',
				'size' => 'small'
			));?>
        </td>
        </tr>
            <?php endforeach; ?>
        </table>
    </div><!-- /span -->
</div> <!-- /row -->

