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
							<a id="db" href="<?=\Yii::$app->homeUrl;?>#db">
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
								<li><a id="sys_role" href="<?=\Yii::$app->homeUrl?>admin/role#sys_role" ><span class="title">Roles</span></a></li>
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
								<span class="title">Users</span>
							</a>
							<!--start submenu -->
							<ul>

							<?php if(\Yii::$app->user->can('superadmin')){ ?>
								<li><a id="all_usrs" href="<?=\Yii::$app->homeUrl?>user/user#all_usrs" ><span class="title">All Users</span></a></li>
								<li><a id="add_usr" href="<?=\Yii::$app->homeUrl?>user/user/create#add_usr" ><span class="title">Add User</span></a></li>
							<?php } else if(\Yii::$app->user->can('company_admin')) { ?>
								<li><a id="all_usrs" href="<?=\Yii::$app->homeUrl?>user/company/index-user#all_usrs" ><span class="title">Users</span></a></li>
								<li><a id="add_usr" href="<?=\Yii::$app->homeUrl?>user/company/create-user#add_usr" ><span class="title">Add User</span></a></li>
							<?php } else if(\Yii::$app->user->can('assessor')) { ?>
								<li><a id="all_usrs" href="<?=\Yii::$app->homeUrl?>user/company/index-role-user#all_usrs" ><span class="title">Users</span></a></li>
								<li><a id="my_profile" href="<?=\Yii::$app->homeUrl?>user/company/my-profile#my_profile" ><span class="title">My Profile</span></a></li>								
							<?php } ?>																		
							</ul><!--end /submenu -->
						</li><!--end /menu-li -->
						<?php if(\Yii::$app->user->can('company manage')){ ?>
						<li class="gui-folder">
							<a>
								<div class="gui-icon"><i class="md md-view-list"></i></div>
								<span class="title">Program Management</span>
							</a>
							<ul>
							<!--start submenu -->
								<li><a id="alpgs" href="<?=\Yii::$app->homeUrl?>course/program/company-programs#alpgs" ><span class="title">Programs</span></a></li>
								<li><a id="add_pgm" href="<?=\Yii::$app->homeUrl?>course/program/create#add_pgm" ><span class="title">Add Program</span></a></li>
								<li><a id="add_lsn" href="<?=\Yii::$app->homeUrl?>course/module/create#add_lsn" ><span class="title">Add Lessons</span></a></li>								
							</ul>
						</li>						
						<?php } else if(\Yii::$app->user->can('company_admin')) { ?>
						<!-- For company admin -->
						<li class="gui-folder">
							<a>
								<div class="gui-icon"><i class="md md-view-list"></i></div>
								<span class="title">Program Management</span>
							</a>
							<ul>
							<!--start submenu -->
								<li><a id="alpgs" href="<?=\Yii::$app->homeUrl?>course/program/company-programs#alpgs" ><span class="title">Programs</span></a></li>
								<li><a id="add_pgm" href="<?=\Yii::$app->homeUrl?>course/program/create#add_pgm"><span class="title">Add Program</span></a></li>
								<li><a id="add_course" href="<?=\Yii::$app->homeUrl?>course/module/create#add_course"><span class="title">Add Course</span></a></li>
								<li><a id="enrl" href="<?=\Yii::$app->homeUrl?>user/company/enroll-user#enrl" ><span class="title">Enroll User</span></a></li>
								<li><a id="ar" href="<?=\Yii::$app->homeUrl?>course/report/assessor-report#ar" ><span class="title">Assessor Reports</span></a></li>
								<li><a id="reset" href="<?=\Yii::$app->homeUrl?>course/report/reset-programs#reset" ><span class="title">Reset Programs</span></a></li>
								<li><a id="reset_m" href="<?=\Yii::$app->homeUrl?>course/report/reset-modules#reset_m" ><span class="title">Reset Lessons</span></a></li>
								<li><a id="reset_un" href="<?=\Yii::$app->homeUrl?>course/report/reset-units#reset_un" ><span class="title">Reset Units</span></a></li>
								<li><a id="reset_u" href="<?=\Yii::$app->homeUrl?>course/report/reset-users#reset_u" ><span class="title">Reset Users</span></a></li>
							
							</ul>
						</li>
						<!-- END Admin -->
						<?php } else if(\Yii::$app->user->can('assessor')) {?>	
						<li class="gui-folder">
							<a>
								<div class="gui-icon"><i class="md md-view-list"></i></div>
								<span class="title">Programs</span>
							</a>
							<ul>
							<!--start submenu -->
							<li><a id="enrl" href="<?=\Yii::$app->homeUrl?>user/company/enroll-user#enrl" ><span class="title">Enroll User</span></a></li>
							<li><a id="ar" href="<?=\Yii::$app->homeUrl?>course/report/assessor-report#ar" ><span class="title">Assessor Reports</span></a></li>
							<li><a id="ex" href="<?=\Yii::$app->homeUrl?>user/importuser/emportexcel#ex" ><span class="title">Export Excel</span></a></li>
							</ul>
						</li>						
						<?php } ?>
						<!-- BEGIN Company -->
						<!-- <li class="gui-folder"> -->
						
						<?php if(\Yii::$app->user->can('company manage')){ ?>
						<li class="gui-folder">
							<a>
								<div class="gui-icon"><i class="md md-work"></i></div>
								<span class="title">Company Management</span>
							</a>
							<ul>
								<li><a href="<?=\Yii::$app->homeUrl?>user/company/index#alcmp" id="alcmp"><span class="title">All Companies</span></a></li>
								<li><a href="<?=\Yii::$app->homeUrl?>user/company/create#addcmp" id="addcmp"><span class="title">Add Company</span></a></li>
								<li><a href="<?=\Yii::$app->homeUrl?>user/role#role" id="role"><span class="title">Roles</span></a></li>
								<li><a href="<?=\Yii::$app->homeUrl?>user/location#loc" id="loc"><span class="title">Locations</span></a></li>
								<li><a href="<?=\Yii::$app->homeUrl?>user/division#div" id="div"><span class="title">Divisions</span></a></li>
								<li><a href="<?=\Yii::$app->homeUrl?>user/state#st" id="st"><span class="title">States</span></a></li>
								
							</ul>
						</li>
						<?php } else if(\Yii::$app->user->can('company_admin')) { ?>
						<li class="gui-folder">
							<a>
								<div class="gui-icon"><i class="md md-work"></i></div>
								<span class="title">Company</span>
							</a>
							<!--start submenu -->
							<ul>
								<li><a href="<?=\Yii::$app->homeUrl?>user/company/view?id=<?= Yii::$app->user->identity->c_id;?>#view_com" id="view_com"><span class="title">Company Profile</span></a></li>
								<li><a href="<?=\Yii::$app->homeUrl?>user/role#role" id="role"><span class="title">Role</span></a></li>
								<li><a href="<?=\Yii::$app->homeUrl?>user/location#loc" id="loc"><span class="title">Location</span></a></li>
								<li><a href="<?=\Yii::$app->homeUrl?>user/division#div" id="div"><span class="title">Division</span></a></li>
								<li><a href="<?=\Yii::$app->homeUrl?>user/state#st" id="st"><span class="title">State</span></a></li>
								
							</ul>
						</li>
						<?php } ?>						
						<!--end /menu-li -->
						<!-- END Company -->
						<li>
							<a href="<?=\Yii::$app->homeUrl?>site/change-password#pwd" id="pwd">
								<div class="gui-icon"><i class="md md-input"></i></div>
								<span class="title">Change Password</span>
							</a>
						</li><!--end /menu-li -->
						<li><?= Html::beginForm(['/site/logout'], 'post')
										. Html::submitButton(
											'Logout',
											['class' => 'btn btn-link logout']
										)
										. Html::endForm()
										?> </li>
					</ul><!--end .main-menu -->
					<!-- END MAIN MENU -->

					<div class="menubar-foot-panel">
						<small class="no-linebreak hidden-folded">
							<span class="opacity-75">Copyright &copy; 2016</span> <strong>MYCAAR</strong>
						</small>
					</div>
				</div><!--end .menubar-scroll-panel-->
			</div><!--end #menubar-->
			<!-- END MENUBAR -->

		</div><!--end #base-->
		<!-- END BASE -->
		<!-- Menu Script -->
		<script>
/* 		$('.gui-controls a').on("click",function(){
			console.log("clicked");
			$(this).addClass("active");
		}) */
		var hash = window.location.hash;
		hash = hash.replace('#', '');
		console.log(hash);
		$("#"+hash).addClass("active");
/* 		switch(hash){
			case("#db"):
				$("$"+hash).addClass("active");
				break;
		} */
		</script>

	</body>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
