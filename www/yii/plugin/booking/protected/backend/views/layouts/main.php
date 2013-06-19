<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div class="container" id="page">


<div style="margin-top:50px">


	<?php
	$sid = "";
	// If not logged in (ie just viewing the calendar) we need a SID
	if (Yii::app()->user->isGuest)
	{
		if (isset($_GET['sid']))
		{
			$sid = "/?sid=" . $_GET['sid'];
            unset(Yii::app()->session['uid']);
            Yii::log("Preprocess request - We have been given new sid " . $_GET['sid'], CLogger::LEVEL_WARNING, 'system.test.kim');

            $criteria = new CDbCriteria;
            $criteria->addCondition("sid = '" . $_GET['sid'] . "'");
            $user = User::model()->find($criteria);
            if ($user == null)
            {
                Yii::log("Preprocess request - This sid is invalid. Aborting" , CLogger::LEVEL_WARNING, 'system.test.kim');
                throw new CHttpException(500,'Cannot continue without a valid sid');
            }
            Yii::app()->session['uid'] = $user->id;
            Yii::app()->session['uid_email'] = $user->email_address;
            Yii::app()->session['uid_name'] = $user->display_name;
            Yii::app()->session['sid'] = $_GET['sid'];  // @@ Set sid too 'cos iframes not trusted. Google 'P3P'
            Yii::log("Preprocess request - sid validated. Setting uid to " . Yii::app()->session['uid'], CLogger::LEVEL_WARNING, 'system.test.kim');
        }
	}
	?>

	<?php $this->widget('bootstrap.widgets.TbNavbar', array(
	'type' => 'null', // null or 'inverse'
	'brand' => 'Home',
	'brandUrl' => array('/site/index' . $sid),
	'collapse' => true, // requires bootstrap-responsive.css
	'items' => array(
		array(
			'class' => 'bootstrap.widgets.TbMenu',
			'items' => array(
				array('label' => 'Calendar', 'url' => array('/site/calendar' . $sid), ),
				array('label' => 'Rooms', 'url' => array('/room/admin'), 'visible' => !Yii::app()->user->isGuest),
				array('label' => 'Extras', 'url' => array('/extra/admin'), 'visible' => !Yii::app()->user->isGuest),
				array('label' => 'Facilities', 'url' => array('/facility/admin'), 'visible' => !Yii::app()->user->isGuest),
				array('label' => 'Occupancy Types', 'url' => array('/occupancyType/admin'), 'visible' => !Yii::app()->user->isGuest),
				array('label' => 'Coupons', 'url' => array('/coupon/admin'), 'visible' => !Yii::app()->user->isGuest),
				array('label' => 'Settings', 'url' => array('/param/index'), 'visible' => !Yii::app()->user->isGuest),
				array('label' => 'T&Cs', 'url' => array('/document/tandc'), 'visible' => !Yii::app()->user->isGuest),
				array('label' => 'Login', 'url' => array('/site/login' . $sid), 'visible' => Yii::app()->user->isGuest),
				array('label' => 'Logout', 'url' => array('/site/logout'), 'visible' => !Yii::app()->user->isGuest),
				array('label' => 'Contact', 'url' => array('/site/contact' . $sid), 'visible' => Yii::app()->user->isGuest),
				//array('label' => 'About', 'url' => array('/site/page', 'view' => 'about'), 'visible' => Yii::app()->user->isGuest),
			),
		),
/*****
		array(
			'class' => 'bootstrap.widgets.TbMenu',
			'htmlOptions' => array('class' => 'pull-right'),
			'items' => array(
				array('label' => 'Link', 'url' => '#'),
				'---',
				array('label' => 'Dropdown', 'url' => '#', 'items' => array(
					array('label' => 'Action', 'url' => '#'),
					array('label' => 'Another action', 'url' => '#'),
					array('label' => 'Something else here', 'url' => '#'),
					'---',
					array('label' => 'Separated link', 'url' => '#'),
				)),
			),
		),
*****/
	),
)); ?>


        </div><!-- navbar margin adjust -->

	<?php if (isset($this->breadcrumbs)): ?>
		<?php $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
		'links' => $this->breadcrumbs,
	)); ?><!-- breadcrumbs -->
	<?php endif?>

	<?php echo $content; ?>
	<div class="clear"></div>

	<div id="footer">
		Copyright &copy; <?php echo date('Y'); ?> by Wirefly Design.<br/>
		All Rights Reserved.<br/>
	</div><!-- footer -->

</div><!-- page -->

</body>
</html>
