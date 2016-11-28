<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use common\models\Company;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\SearchProgram */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Reports';
$this->params['breadcrumbs'][] = $this->title;
?>
    <h1><?= Html::encode($this->title) ?></h1>
<div class="card">

	<div class ="card-body">


    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php //print_r($dataProvider->models);?>
    </p>
	
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
       // 'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'awareness_progress',
            'capability_progress',
            'user_id',
			'unit_id',
/* 			[
				'attribute' => 'company_id',
				'value' => 'company.name',
				'filter' => Html::activeDropDownList($searchModel, 'company_id', ArrayHelper::map(Company::find()->asArray()->all(), 'company_id', 'name'),['class'=>'form-control input-sm','prompt' => 'Company']),
			], */
           // 'description:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
	</div>
</div>





            <div class="section-body">

                <div class="mdl-grid mdl-home">
                    <div class="mdl-cell mdl-cell-8-col mdl-home1" >
                        <h1><strong>Home Page</strong></h1>
                    </div>
                    <div class="mdl-cell mdl-cell-4-col mdl-section">
                        <ul class="mdl-main">
                            <li>
                                <button class="mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab mdl-button--colored mdl-hover-fabelgreen mdl-icon" >Green</button><span class="mdl-complete">Complete</span>
                                <button class="mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab mdl-button--colored mdl-hover-fabelyellow mdl-yellow" > Amber</button><span class="mdl-complete">In Progress</span>
                                <button class="mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab mdl-button--colored mdl-hover-fabelred mdl-darkred" >Red</button><span class="mdl-complete">- Not Commenced</span>
                                <button class="mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab mdl-button--colored mdl-hover-fabelgrey mdl-lightgrey" >Grey</button><span class="mdl-complete">- Not applicable</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="mdl-grid">
                <div class="mdl-cell mdl-cell-8-col">
                    <span class="mdl-welcome"><h3>Welcome rahul dravid</h3></span>
                    <span class="mdl-current"><h3>Current Programs :</h3></span>
                    <span class="mdl-program"><h4 style="font-size:18px"><span class="mdl-test">Program</span> : checktest1</h4></span>
                </div>
            </div>


            <div class="horizontal al_cpp_category_16">
                <div class="all_course al_pragram_width ">
                    <div class="course_listing al_single_course_width units-present-4">

                        <div class="course_name">
                            <h2>
                                <strong>module i of checktest1</strong>
                            </h2>
                        </div>

                        <div class="course_units">
                            <ul>
                                <li>
                                    <div class="single_unit_title">
                                        1
                                    </div>
                                    <div class="course_types">
                                        <div class="course_indicate">
                                            <div class="assessement_item">
                                                <div name="unit1">

                                                    <span class="first_heading">Aware</span>

                                                    <div name="unit1">
                                                        <a class="mdl-button mdl-js-button mdl-button--fab mdl-hover mdl-small-icon" href=""><span class="tooltiptext"><center>Amber</center></span>
                                                        </a>
                                                    </div>

                                                </div>

                                                <div name="unit1">
                                                    <span class="first_heading">Capable</span>

                                                    <div name="unit1">

                                                        <a class="mdl-button mdl-js-button mdl-button--fab mdl-hover-red mdl-small-icon-red" href=""><span class="toolkit"><center>Red</center></span>
                                                        </a>

                                                    </div>
                                                </div>


                                            </div>
                                        </div>

                                        <!--2nd row is starting-->

                                        <div class="course_indicate">
                                            <div class="assessement_item">
                                                <div name="unit1">

                                                    <span class="first_heading" style="display: none">Aware</span>

                                                    <div name="unit1">

                                                        <a class="mdl-button mdl-js-button mdl-button--fab mdl-hover mdl-small-icon" href=""><span class="tooltiptext"><center>Amber</center></span>
                                                        </a>

                                                    </div>
                                                </div>
                                                <div name="unit1">
                                                    <span class="first_heading" >Capable</span>

                                                    <div name="unit1">

                                                        <a class="mdl-button mdl-js-button mdl-button--fab mdl-hover-red mdl-small-icon-red"><span class="toolkit"><center>Red</center></span>
                                                        </a>

                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <!--end of 2nd row-->

                                        <!--start of 3rd row-->
                                        <div class="course_indicate">
                                            <div class="assessement_item">
                                                <div name="unit1">

                                                    <span class="first_heading" style="display: none">Aware</span>

                                                    <div name="unit1">

                                                        <a class="mdl-button mdl-js-button mdl-button--fab mdl-hover mdl-small-icon" href=""><span class="tooltiptext"><center>Amber</center></span>
                                                        </a>

                                                    </div>
                                                </div>
                                                <div name="unit1">
                                                    <span class="first_heading" >Capable</span>

                                                    <div name="unit1">

                                                        <a class="mdl-button mdl-js-button mdl-button--fab mdl-hover-red mdl-small-icon-red"><span class="toolkit"><center>Red</center></span>
                                                        </a>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end of 3rd row-->
                                </li>

                                <li class="margin" style="margin-left: -307px">
                                    <div class="single_unit_title">
                                        2
                                    </div>
                                    <div class="course_types">
                                        <div class="course_indicate">
                                            <div class="assessement_item">
                                                <div name="unit1">

                                                    <span class="first_heading">Aware</span>

                                                    <div name="unit1">
                                                        <a class="mdl-button mdl-js-button mdl-button--fab mdl-hover-green mdl-small-icon-green"><span class="toolkit"><center>Amber</center></span>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div name="unit1">
                                                    <span class="first_heading">Capable</span>

                                                    <div name="unit1">

                                                        <a class="mdl-button mdl-js-button mdl-button--fab mdl-hover-grey mdl-small-icon-grey"><span class="toolkit"><center>Grey</center></span>
                                                        </a>

                                                    </div>
                                                </div>


                                            </div>
                                        </div>

                                        <!--2nd row is starting-->

                                        <div class="course_indicate">
                                            <div class="assessement_item">
                                                <div name="unit1">

                                                    <span class="first_heading" style="display: none">Aware</span>

                                                    <div name="unit1">

                                                        <a class="mdl-button mdl-js-button mdl-button--fab mdl-hover mdl-small-icon" href=""><span class="tooltiptext"><center>Amber</center></span>
                                                        </a>

                                                    </div>
                                                </div>
                                                <div name="unit1">
                                                    <span class="first_heading" >Capable</span>

                                                    <div name="unit1">

                                                        <a class="mdl-button mdl-js-button mdl-button--fab mdl-hover-red mdl-small-icon-red"><span class="toolkit"><center>Red</center></span>
                                                        </a>

                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <!--end of 2nd row-->
                                        <!--start of 3rd row-->
                                        <div class="course_indicate">
                                            <div class="assessement_item">
                                                <div name="unit1">

                                                    <span class="first_heading" style="display: none">Aware</span>

                                                    <div name="unit1">

                                                        <a class="mdl-button mdl-js-button mdl-button--fab mdl-hover mdl-small-icon" href=""><span class="tooltiptext"><center>Amber</center></span>
                                                        </a>

                                                    </div>
                                                </div>
                                                <div name="unit1">
                                                    <span class="first_heading" >Capable</span>

                                                    <div name="unit1">

                                                        <a class="mdl-button mdl-js-button mdl-button--fab mdl-hover-red mdl-small-icon-red"><span class="toolkit"><center>Red</center></span>
                                                        </a>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end of 3rd row-->

                                    </div>
                                </li>

                                

                            </ul>
                        </div>
                    </div>


                    <!--2nd module start-->
                    <div class="course_listing al_single_course_width units-present-4" style="margin-left: -7px">
                        <div class="course_name">
                            <h2>
                                <strong>module 2</strong>
                            </h2>
                        </div>

                        <div class="course_units">
                            <ul>
                                <li>
                                    <div class="single_unit_title">
                                        3
                                    </div>
                                    <div class="course_types">
                                        <div class="course_indicate">
                                            <div class="assessement_item">
                                                <div name="unit1">

                                                    <span class="first_heading">Aware</span>

                                                    <div name="unit1">
                                                        <a class="mdl-button mdl-js-button mdl-button--fab mdl-hover mdl-small-icon" href=""><span class="tooltiptext"><center>Amber</center></span>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div name="unit1">
                                                    <span class="first_heading">Capable</span>

                                                    <div name="unit1">

                                                        <a class="mdl-button mdl-js-button mdl-button--fab mdl-hover-red mdl-small-icon-red"><span class="toolkit"><center>Red</center></span>
                                                        </a>

                                                    </div>
                                                </div>


                                            </div>
                                        </div>

                                        <!--2nd row is starting-->

                                        <div class="course_indicate">
                                            <div class="assessement_item">
                                                <div name="unit1">

                                                    <span class="first_heading" style="display: none">Aware</span>

                                                    <div name="unit1">

                                                        <a class="mdl-button mdl-js-button mdl-button--fab mdl-hover mdl-small-icon" href=""><span class="tooltiptext"><center>Amber</center></span>
                                                        </a>

                                                    </div>
                                                </div>
                                                <div name="unit1">
                                                    <span class="first_heading" >Capable</span>

                                                    <div name="unit1">

                                                        <a class="mdl-button mdl-js-button mdl-button--fab mdl-hover-red mdl-small-icon-red"><span class="toolkit"><center>Red</center></span>
                                                        </a>

                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <!--end of 2nd row-->
                                        <!--start of 3rd row-->
                                        <div class="course_indicate">
                                            <div class="assessement_item">
                                                <div name="unit1">

                                                    <span class="first_heading" style="display: none">Aware</span>

                                                    <div name="unit1">

                                                        <a class="mdl-button mdl-js-button mdl-button--fab mdl-hover mdl-small-icon" href=""><span class="tooltiptext"><center>Amber</center></span>
                                                        </a>

                                                    </div>
                                                </div>
                                                <div name="unit1">
                                                    <span class="first_heading" >Capable</span>

                                                    <div name="unit1">

                                                        <a class="mdl-button mdl-js-button mdl-button--fab mdl-hover-red mdl-small-icon-red"><span class="toolkit"><center>Red</center></span>
                                                        </a>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end of 3rd row-->

                                    </div>
                                </li>

   
                            </ul>

                        </div>
                    </div>

                </div>

            </div>
            <div class="mdl-grid">
                <div class="mdl-cell mdl-cell--3-col mdl-bar">
                    <div class="mdl-card--border" style="border: 1px solid #008000;margin:6px;height:27px"><span class="mdl-text">0%</span><span class="mdl-label">Rahul Dravid</span></div>
                </div>
            </div>

            <div class="mdl-grid">
                <div class="mdl-cell mdl-cell--3-col mdl-bar1">
                    <div class="mdl-card--border" style="border: 1px solid #008000;margin:6px;height:27px"><span class="mdl-text">0%</span><span class="mdl-label">Rahul Dravid</span></div>
                </div>
            </div>
            <div class="mdl-grid">
                <div class="mdl-cell mdl-cell--3-col mdl-bar2">
                    <div class="mdl-card--border" style="border: 1px solid #008000;margin:6px;height:27px"><span class="mdl-text">0%</span><span class="mdl-label">Rahul Dravid</span></div>
                </div>
            </div>