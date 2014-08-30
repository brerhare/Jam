<?php
	if ($model->active)
		echo $this->renderPartial('_form', array('model'=>$model, 'somedata'=>$somedata));
	else
		echo "<h6 style='color:#ff0000'><center>Sorry... ticket sales for this event are inactive. This usually means it has sold out or started :-(</center></h6>";
?>
