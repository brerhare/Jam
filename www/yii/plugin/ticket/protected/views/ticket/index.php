<!-- Display a list of events to pick from -->

<style>
#page {
	border:0;
}
</style>

<div class="row">
    <div class="span6 well" style="padding-top:0px;padding-bottom:0px">
<center>
        <h4 style="color:#a6a6a6">Available Events</h4> 
</center>
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
        <tr style="height:120px">
        <td width="30%" style="text-align:center;">
			<?php
			$thumbPath = Yii::app()->basePath . "/../../" . $logo;
			if (file_exists($thumbPath))
			{
				$imgdim = getimagesize(Yii::app()->basePath . "/../../" . $logo);
				$imgw=$imgdim[0];
				$imgh=$imgdim[1];
				$imgstr="height:100px;";
				if ($imgh<=100)
				if ($imgw>100)
					$imgstr="width:100%;";
				echo CHtml::image(
					$logo,
					'Event Image',
					array('style'=>$imgstr));
			}
			?>
        </td>
        <td width="60%">
        	<div style="margin:0; padding:0; width:100%;height:100px;overflow:hidden;">
                <b><?php echo $event->title; ?></b>
                <br>
                <i><?php echo $event->date; ?></i>
                <div style="padding:0; margin:0; color:#696d6e; font-size:95%">
                    <?php echo nl2br($event->banner_text); ?>
                </div>
        	</div>
        </td>
        <td width="10%">
        	<?php
        	$ref="none";
        	if ((isset($_GET['ref'])) && ($_GET['ref'] == 'bktji5308'))
        		$ref=$_GET['ref'];
        	?>
			<?php $this->widget('bootstrap.widgets.TbButton',array(
				'label' => 'Buy',
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

<!-- @@NB iframe resizer hardcode here -->
<script type="text/javascript" src="/js/iframeResizer.contentWindow.min.js"></script>
    <!-- Iframe resizer -->
    <script type="text/javascript" src="/js/jquery.iframeResizer.min.js"></script>
    <script type="text/javascript">
        jQuery('iframe').iFrameSizer({
            log                    : true,  // For development
            autoResize             : true,  // Trigering resize on events in iFrame
            contentWindowBodyMargin: 8,     // Set the default browser body margin style (in px)
            doHeight               : true,  // Calculates dynamic height
            doWidth                : false, // Calculates dynamic width
            enablePublicMethods    : true,  // Enable methods within iframe hosted page
            interval               : 0,     // interval in ms to recalculate body height, 0 to disable refreshing
            scrolling              : false, // Enable the scrollbars in the iFrame
            callback               : function(messageData){ // Callback fn when message is received
                $('p#callback').html(
                    '<b>Frame ID:</b> '    + messageData.iframe.id +
                    ' <b>Height:</b> '     + messageData.height +
                    ' <b>Width:</b> '      + messageData.width +
                    ' <b>Event type:</b> ' + messageData.type
                );
            }
        });
</script>


