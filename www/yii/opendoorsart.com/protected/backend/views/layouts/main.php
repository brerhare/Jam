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

	<?php $this->widget('bootstrap.widgets.TbNavbar', array(
	'type' => 'null', // null or 'inverse'
	'brand' => 'Home',
	'brandUrl' => array('/site/index'),
	'collapse' => true, // requires bootstrap-responsive.css
	'items' => array(
		array(
			'class' => 'bootstrap.widgets.TbMenu',
			'items' => array(
                array('label' => 'Pages', 'url' => array('/contentBlock/admin'), 'visible' => !Yii::app()->user->isGuest),
                array('label' => 'Members', 'url' => array('/member/admin'), 'visible' => !Yii::app()->user->isGuest),
                array('label' => 'Categories', 'url' => array('/category/admin'), 'visible' => !Yii::app()->user->isGuest),
                array('label' => 'Types', 'url' => array('/foodType/admin'), 'visible' => !Yii::app()->user->isGuest),
                //array('label' => 'Image Sliders', 'url' => array('/jellySliderImage/admin'), 'visible' => !Yii::app()->user->isGuest),
                //array('label' => 'HTML Sliders', 'url' => array('/jellySliderHtml/admin'), 'visible' => !Yii::app()->user->isGuest),
                //array('label' => 'Tabs', 'url' => array('/tabBlock/admin'), 'visible' => !Yii::app()->user->isGuest),                      
                array('label' => 'Login', 'url' => array('/site/login'), 'visible' => Yii::app()->user->isGuest),
                array('label' => 'Logout', 'url' => array('/site/logout'), 'visible' => !Yii::app()->user->isGuest),                 
                array('label' => 'Contact', 'url' => array('/site/contact'), 'visible' => Yii::app()->user->isGuest),                
                array('label' => 'About', 'url' => array('/site/page', 'view' => 'about'), 'visible' => Yii::app()->user->isGuest),  
			),
		),

		// Plugin dropdown menu
		array(
			'class' => 'bootstrap.widgets.TbMenu',
			'htmlOptions' => array('class' => 'pull-right'),
			'items' => array(
				array('label' => 'Plugins', 'url' => '#', 'items' => array(
				array('label' => 'Blog', 'url' => 'https://plugin.wireflydesign.com/blog/backend.php/site/opendoorsart', 'linkOptions' => array("target"=>"_blank"), 'visible' => !Yii::app()->user->isGuest),
				array('label' => 'Mailer', 'url' => 'https://plugin.wireflydesign.com/mailer/backend.php/site/opendoorsartDirect', 'linkOptions' => array("target"=>"_blank"), 'visible' => !Yii::app()->user->isGuest),
					//'---',
					//array('label' => 'Separated link', 'url' => '#'),
				)),
			),
		),

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
		Copyright &copy; <?php echo date('Y'); ?> Wirefly Design.<br/>
		All Rights Reserved.<br/>
	</div><!-- footer -->

</div><!-- page -->

</body>
</html>