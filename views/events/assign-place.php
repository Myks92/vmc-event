<?php

use kartik\form\ActiveForm;
use Myks92\Vmc\Activity\Widget\Members\CitySelect;
use Myks92\Vmc\Event\Model\Entity\Events\Event;
use yii\helpers\Html;

/* @var yii\web\View $this */
/* @var \Myks92\Vmc\Event\Model\UseCase\Events\Place\Assign\Form $assignForm */
/* @var Event $model */

$this->title = Yii::t('event-event-place', 'TITLE_PLACE_ADD');
$this->params['breadcrumbs'][] = ['label' => Yii::t('event-event', 'TITLE_EVENTS'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->getName(), 'url' => ['view', 'id' => $model->getId()->getValue()]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="competition-create">

    <div class="card shadow-sm mb-3">
        <div class="card-header">
            <h4 class="float-left"><?= $this->title ?></h4>
        </div>
        <div class="card-body">

            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($assignForm, 'name')->textInput()->hint('Введите название площадки. Например, Большой зал') ?>
            <?= $form->field($assignForm, 'city')->widget(CitySelect::class) ?>
            <?= $form->field($assignForm, 'street')->textInput()->hint('Введите улицу и дом. Например, ул. Мира, д. 46') ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('event-event-place', 'LINK_SAVE'), ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>

</div>