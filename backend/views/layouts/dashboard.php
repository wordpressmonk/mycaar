<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use backend\assets\AppAsset;

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
									<li class="dropdown-header">Config</li>
									<li><a href="../../html/pages/profile.html">My profile</a></li>
									<li><a href="../../html/pages/blog/post.html">My blog <span class="badge style-danger pull-right">16</span></a></li>
									<li><a href="../../html/pages/calendar.html">My appointments</a></li>
									<li class="divider"></li>
									<li><a href="../../html/pages/locked.html"><i class="fa fa-fw fa-lock"></i> Lock</a></li>

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
		<div id="base">

			<!-- BEGIN OFFCANVAS LEFT -->
			<div class="offcanvas">
			</div><!--end .offcanvas-->
			<!-- END OFFCANVAS LEFT -->

			<!-- BEGIN CONTENT-->
			<div id="content">
				<section>
					<?= $content ?>
				</section>
			</div><!--end #content-->
			<!-- END CONTENT -->

			<!-- BEGIN MENUBAR-->
			<div id="menubar" class="menubar-inverse ">
				<div class="menubar-fixed-panel">
					<div>
						<a class="btn btn-icon-toggle btn-default menubar-toggle" data-toggle="menubar" href="javascript:void(0);">
							<i class="fa fa-bars"></i>
						</a>
					</div>
					<div class="expanded">

						<a href="<?=\Yii::$app->homeUrl;?>">
							<span class="text-lg text-bold text-primary ">MY&nbsp;CAAR</span>
						</a>
					</div>
				</div>
				<div class="menubar-scroll-panel">

					<!-- BEGIN MAIN MENU -->
					<ul id="main-menu" class="gui-controls">

						<!-- BEGIN DASHBOARD -->
						<li>

							<a href="<?=\Yii::$app->homeUrl;?>" >
								<div class="gui-icon"><i class="md md-home"></i></div>
								<span class="title">Dashboard</span>
							</a>
						</li><!--end /menu-li -->
						<!-- END DASHBOARD -->
						<?php if(\Yii::$app->user->can('rbac permissions')){ ?>
						<!-- BEGIN System -->						
						<li class="gui-folder">
							<a>
								<div class="gui-icon"><i class="md md-settings"></i></div>
								<span class="title">System</span>
							</a>
							<!--start submenu -->
							<ul>
								<li><a href="<?=\Yii::$app->homeUrl?>admin/role" ><span class="title">Roles</span></a></li>
								<li><a href="<?=\Yii::$app->homeUrl?>admin/permission" ><span class="title">Permissions</span></a></li>
								<li><a href="<?=\Yii::$app->homeUrl?>admin/assignment" ><span class="title">Assignments</span></a></li>
								<li><a href="<?=\Yii::$app->homeUrl?>admin/rule" ><span class="title">Rules</span></a></li>
							</ul><!--end /submenu -->
						</li><!--end /menu-li -->
						<!-- END System -->
						<?php } ?>
						<!-- BEGIN Admin -->						
						<li class="gui-folder">
							<a>
								<div class="gui-icon"><i class="md md-settings"></i></div>
								<span class="title">Admin</span>
							</a>
							<!--start submenu -->
							<ul>

							<?php if(\Yii::$app->user->can('superadmin')){ ?>
								<li><a href="<?=\Yii::$app->homeUrl?>user/user" ><span class="title">Users</span></a></li>

							<?php } else if(\Yii::$app->user->can('company_admin')) { ?>
								<li><a href="<?=\Yii::$app->homeUrl?>user/company/index-user" ><span class="title">Users</span></a></li>
								<li><a href="<?=\Yii::$app->homeUrl?>user/company/enroll-user" ><span class="title">Enroll User</span></a></li>
								<li><a href="<?=\Yii::$app->homeUrl?>course/report/search" ><span class="title">Reports</span></a></li>
								<li><a href="<?=\Yii::$app->homeUrl?>course/report/reset-programs" ><span class="title">Reset Programs</span></a></li>
		
						<?php } ?>							
								<li><a href="<?=\Yii::$app->homeUrl?>course/program/company-programs" ><span class="title">Programs</span></a></li>
								<li><a href="<?=\Yii::$app->homeUrl?>course/program/create" ><span class="title">Add Program</span></a></li>
								
								<li><a href="<?=\Yii::$app->homeUrl?>user/division" ><span class="title">Division</span></a></li>
								<li><a href="<?=\Yii::$app->homeUrl?>user/location" ><span class="title">Location</span></a></li>
								<li><a href="<?=\Yii::$app->homeUrl?>user/state" ><span class="title">State</span></a></li>
								<li><a href="<?=\Yii::$app->homeUrl?>user/role" ><span class="title">Role</span></a></li>
								<li><a href="<?=\Yii::$app->homeUrl?>site/change-password" ><span class="title">Change Password</span></a></li>
								
							</ul><!--end /submenu -->
						</li><!--end /menu-li -->

						<!-- END Admin -->
						<!-- BEGIN Company -->
						<!-- <li class="gui-folder"> -->
						
						<?php if(\Yii::$app->user->can('company manage')){ ?>
						<li>
							<a href="<?=\Yii::$app->homeUrl?>user/company/index"  >
								<div class="gui-icon"><i class="fa fa-user"></i></div>
								<span class="title">Company Management</span>
							</a>						
						</li>
						<?php } else if(\Yii::$app->user->can('company_admin')) { ?>
						
						<li>
							<a href="<?=\Yii::$app->homeUrl?>user/company/view?id=<?= Yii::$app->user->identity->c_id;?>"  >
								<div class="gui-icon"><i class="fa fa-user"></i></div>
								<span class="title">Company Details</span>
							</a>						
						</li>
						<?php } ?>
						
						<!--end /menu-li -->
						<!-- END Company -->

					</ul><!--end .main-menu -->
					<!-- END MAIN MENU -->

					<div class="menubar-foot-panel">
						<small class="no-linebreak hidden-folded">
							<span class="opacity-75">Copyright &copy; 2016</span> <strong>CodeCovers</strong>
						</small>
					</div>
				</div><!--end .menubar-scroll-panel-->
			</div><!--end #menubar-->
			<!-- END MENUBAR -->

		</div><!--end #base-->
		<!-- END BASE -->


	</body>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
