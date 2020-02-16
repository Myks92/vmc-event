<?php

use kartik\form\ActiveForm;
use Myks92\Vmc\Event\Assets\EventAssets;
use Myks92\Vmc\Event\ReadModel\Events\Filter;
use Myks92\Vmc\Event\Security\Access\Events\EventChecker;
use yii\bootstrap4\Nav;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var yii\web\View $this */
/* @var Filter $searchModel */
/* @var yii\data\ActiveDataProvider $dataProvider */
/* @var EventChecker $checker */

EventAssets::register($this);

$this->title = Yii::t('event-event', 'TITLE_EVENTS');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-index">

    <div class="row">
        <div class="col-sm-9">
            <div class="card shadow-sm mb-3">
                <div class="tab-card-header">
                    <?= Nav::widget([
                        'items' => [
                            [
                                'label' => 'Предстоящие',
                                'url' => ['index'],
                                'active' => $searchModel->tab == $searchModel::TAB_FUTURE
                            ],
                            [
                                'label' => 'Прошедшие',
                                'url' => ['index', 'e' => ['tab' => $searchModel::TAB_PAST]],
                                'active' => $searchModel->tab == $searchModel::TAB_PAST
                            ],
                        ],
                        'options' => ['class' => 'nav nav-tabs']
                    ]) ?>
                </div>
                <div class="card-header pt-4">
                    <?php $form = ActiveForm::begin([
                        'action' => Url::to(['index']),
                        'method' => 'get',
                        'options' => [
                            'class' => 'form-ajax',
                            'data-id-content' => 'search-content'
                        ],
                    ]); ?>

                    <?= $form->field($searchModel, 'filter')
                        ->label(false)
                        ->textInput(['placeholder' => 'Поиск ...']) ?>

                    <?php ActiveForm::end(); ?>
                </div>
                <div class="" id="search-content">
                    <?= $this->render('_list', ['dataProvider' => $dataProvider,
                        'checker' => $checker]) ?>
                </div>
            </div>
            <div class="mb-3">
            </div>
        </div>
        <div class="col-sm-3">
            <?php if ($checker->allowCreate()): ?>
                <?= Html::a(Yii::t('event-event', 'LINK_CREATE'), ['create'], [
                    'class' => 'btn btn-success btn-block mb-3',
                    'title' => Yii::t('event-event', 'LINK_CREATE')
                ]) ?>
            <?php endif; ?>
            <div class="card shadow-sm mb-3">
                <div class="card-header">
                    <div class="float-left"><?= Yii::t('event', 'BLOCK_TITLE_SEARCH') ?></div>
                    <div class="float-right">

                    </div>
                </div>
                <div class="card-body">
                    <?= $this->render('_search', [
                        'model' => $searchModel,
                        'checker' => $checker
                    ]); ?>
                </div>
            </div>

        </div>
    </div>

</div>

