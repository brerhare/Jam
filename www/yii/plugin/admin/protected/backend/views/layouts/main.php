<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="language" content="en"/>

	<link rel="icon" href="<?php echo Yii::app()->request->baseUrl; ?>/favicon.ico" type="image/x-icon"/>
	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css"
	      media="screen, projection"/>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css"
	      media="print"/>
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css"
	      media="screen, projection"/>
	<![endif]-->

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>

	<?php
		$baseUrl = Yii::app()->baseUrl;
		$cs = Yii::app()->getClientScript();
		//$cs->registerScriptFile($baseUrl.'/js/yourscript.js');
		//$cs->registerCssFile($baseUrl.'/css/bootstrap/springTones.css');
		//$cs->registerCssFile($baseUrl.'/css/bootstrap/cerulean.css');
		$cs->registerCssFile($baseUrl.'/css/bootstrap/superhero.css');
	?>

</head>

<body>

<div class="container" id="page">
	<?php $this->widget('bootstrap.widgets.TbNavbar', array(
	'type' => 'null', // null or 'inverse'
	'brand' => 'Project name',
	'brandUrl' => '#',
	'collapse' => true, // requires bootstrap-responsive.css
	'items' => array(
		array(
			'class' => 'bootstrap.widgets.TbMenu',
			'items' => array(
				array('label' => 'Home', 'url' => array('/site/index')),
				array('label' => 'About', 'url' => array('/site/page', 'view' => 'about')),
				array('label' => 'Contact', 'url' => array('/site/contact')),
				array('label' => 'Login', 'url' => array('/site/login'), 'visible' => Yii::app()->user->isGuest),
				array('label' => 'Logout (' . Yii::app()->user->name . ')', 'url' => array('/site/logout'), 'visible' => !Yii::app()->user->isGuest)
			),
		),
		'<form class="navbar-search pull-left" action=""><input type="text" class="search-query span2" placeholder="Search"></form>',
		(!Yii::app()->user->isGuest) ? '<p class="navbar-text pull-right">Logged in as <a href="#">username</a></p>' : '',
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
	),
)); ?>
	<!-- mainmenu -->
	<div class="container" style="margin-top:80px">
		<?php if (isset($this->breadcrumbs)): ?>
			<?php $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
			'links' => $this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
		<?php endif?>

		<?php echo $content; ?>
		<hr/>
		<div id="footer">
			Copyright &copy; <?php echo date('Y'); ?> <a href="http://www.wireflydesign.com" target="_blank">Wirefly Design</a><br/>
			All Rights Reserved.<br/>
		</div>
		<!-- footer -->
	</div>
</div>
<!-- page -->
</body>
</html>
