<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
	<?php if($this->title == 'Reports') { ?>
	<link rel="stylesheet" href="<?=Yii::$app->homeUrl;?>css/custom/buttons.min.css">
	<?php } ?>
    <?php $this->head() ?>

		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		<script type="text/javascript" src="<?=Yii::$app->homeUrl;?>js/libs/utils/html5shiv.js?1403934957"></script>
		<script type="text/javascript" src="<?=Yii::$app->homeUrl;?>js/libs/utils/respond.min.js?1403934956"></script>
		<![endif]-->

</head>
	<body class="menubar-hoverable header-fixed menubar-pin ">
	<?php $this->beginBody() ?>
		<!-- BEGIN HEADER-->
		<header id="header" >
			<div class="headerbar">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="headerbar-left">
					<ul class="header-nav header-nav-options">
						<li class="header-nav-brand" >
							<div class="brand-holder">
								<a href="<?=\Yii::$app->homeUrl;?>">
									<span class="text-lg text-bold text-primary">MY CAAR</span>
								</a>
							</div>
						</li>
						<li>
							<a class="btn btn-icon-toggle menubar-toggle" data-toggle="menubar" href="javascript:void(0);">
								<i class="fa fa-bars"></i>
							</a>
						</li>
					</ul>
				</div>
				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="headerbar-right">
					<ul class="header-nav header-nav-options">
						<li>
							<!-- Search form -->
							<form class="navbar-search" role="search">
								<div class="form-group">
									<input type="text" class="form-control" name="headerSearch" placeholder="Enter your keyword">
								</div>
								<button type="submit" class="btn btn-icon-toggle ink-reaction"><i class="fa fa-search"></i></button>
							</form>
						</li>
						<li class="dropdown hidden-xs">
							<a href="javascript:void(0);" class="btn btn-icon-toggle btn-default" data-toggle="dropdown">
								<i class="fa fa-bell"></i><sup class="badge style-danger">4</sup>
							</a>
							<ul class="dropdown-menu animation-expand">
								<li class="dropdown-header">Today's messages</li>
								<li>
									<a class="alert alert-callout alert-warning" href="javascript:void(0);">
										<img class="pull-right img-circle dropdown-avatar" src="<?=Yii::$app->homeUrl;?>img/avatar2.jpg?1404026449" alt="" />
										<strong>Alex Anistor</strong><br/>
										<small>Testing functionality...</small>
									</a>
								</li>
								<li>
									<a class="alert alert-callout alert-info" href="javascript:void(0);">
										<img class="pull-right img-circle dropdown-avatar" src="<?=Yii::$app->homeUrl;?>img/avatar3.jpg?1404026799" alt="" />
										<strong>Alicia Adell</strong><br/>
										<small>Reviewing last changes...</small>
									</a>
								</li>
								<li class="dropdown-header">Options</li>
								<li><a href="../../html/pages/login.html">View all messages <span class="pull-right"><i class="fa fa-arrow-right"></i></span></a></li>
								<li><a href="../../html/pages/login.html">Mark as read <span class="pull-right"><i class="fa fa-arrow-right"></i></span></a></li>
							</ul><!--end .dropdown-menu -->
						</li><!--end .dropdown -->
						<li class="dropdown hidden-xs">
							<a href="javascript:void(0);" class="btn btn-icon-toggle btn-default" data-toggle="dropdown">
								<i class="fa fa-area-chart"></i>
							</a>
							<ul class="dropdown-menu animation-expand">
								<li class="dropdown-header">Server load</li>
								<li class="dropdown-progress">
									<a href="javascript:void(0);">
										<div class="dropdown-label">
											<span class="text-light">Server load <strong>Today</strong></span>
											<strong class="pull-right">93%</strong>
										</div>
										<div class="progress"><div class="progress-bar progress-bar-danger" style="width: 93%"></div></div>
									</a>
								</li><!--end .dropdown-progress -->
								<li class="dropdown-progress">
									<a href="javascript:void(0);">
										<div class="dropdown-label">
											<span class="text-light">Server load <strong>Yesterday</strong></span>
											<strong class="pull-right">30%</strong>
										</div>
										<div class="progress"><div class="progress-bar progress-bar-success" style="width: 30%"></div></div>
									</a>
								</li><!--end .dropdown-progress -->
								<li class="dropdown-progress">
									<a href="javascript:void(0);">
										<div class="dropdown-label">
											<span class="text-light">Server load <strong>Lastweek</strong></span>
											<strong class="pull-right">74%</strong>
										</div>
										<div class="progress"><div class="progress-bar progress-bar-warning" style="width: 74%"></div></div>
									</a>
								</li><!--end .dropdown-progress -->
							</ul><!--end .dropdown-menu -->
						</li><!--end .dropdown -->
					</ul><!--end .header-nav-options -->
					<?php if(!\Yii::$app->user->isGuest){ ?>					
						<ul class="header-nav header-nav-profile">
							<li class="dropdown">
								<a href="javascript:void(0);" class="dropdown-toggle ink-reaction" data-toggle="dropdown">
									
									<span class="profile-info">
										 <?php echo Yii::$app->user->identity->fullname;?>
										<small><?= $user_role_name = Yii::$app->user->identity->role;?></small>
									</span>
								</a>
									
								<ul class="dropdown-menu animation-dock">
									<!--<li><a href="<?=\Yii::$app->homeUrl?>site/logout"><i class="fa fa-fw fa-power-off text-danger"></i> Logout</a></li>-->
									<li><a href="<?=\Yii::$app->homeUrl?>site/change-password"><i class="fa fa-fw fa-key text-danger"></i> Change Password</a></li>	
									<li><?= Html::beginForm(['/site/logout'], 'post')
										. Html::submitButton(
											'Logout',
											['class' => 'btn btn-link logout']
										)
										. Html::endForm()
										?> </li>
								</ul><!--end .dropdown-menu -->
							</li><!--end .dropdown -->
						</ul><!--end .header-nav-profile -->
					<?php } ?>
					<ul class="header-nav header-nav-toggle">
						<li>
							<a class="btn btn-icon-toggle btn-default" href="#offcanvas-search" data-toggle="offcanvas" data-backdrop="false">
								<i class="fa fa-ellipsis-v"></i>
							</a>
						</li>
					</ul><!--end .header-nav-toggle -->
				</div><!--end #header-navbar-collapse -->
			</div>
		</header>
		<!-- END HEADER-->

		<!-- BEGIN BASE-->
		<div id="base-full">

			<!-- BEGIN OFFCANVAS LEFT -->
			<div class="offcanvas">
			</div><!--end .offcanvas-->
			<!-- END OFFCANVAS LEFT -->

			<!-- BEGIN CONTENT-->
			<div id="content">
				<section>
					<div class="section-body contain-lg">
						<?= $content ?>
					</div>
				</section>
			</div><!--end #content-->
			<!-- END CONTENT -->
		</div>
		<!-- END BASE -->


	</body>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>