<?php

use kartik\form\ActiveForm;
use Myks92\Vmc\Event\Model\Entity\Events\Event;
use yii\helpers\Html;
use yii\widgets\MaskedInput;

/* @var yii\web\View $this */
/* @var \Myks92\Vmc\Event\Model\UseCase\Events\Move\Form $moveForm */
/* @var Event $model */

$this->title = $model->getName();
$this->params['breadcrumbs'][] = ['label' => Yii::t('event-event', 'TITLE_EVENTS'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->getName(), 'url' => ['view', 'id' => $model->getId()->getValue()]];
$this->params['breadcrumbs'][] = Yii::t('event-event', 'TITLE_EVENT_MOVE');
?>
<div class="competition-create">

    <div class="card shadow-sm mb-3">
        <div class="card-header">
            <h4 class="float-left"><?= $this->title ?></h4>
        </div>
        <div class="card-body">

            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($moveForm, 'from')->widget(MaskedInput::class, [
                'mask' => '99.99.9999'
            ]) ?>
            <?= $form->field($moveForm, 'to')->widget(MaskedInput::class, [
                'mask' => '99.99.9999'
            ]) ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('event-event', 'LINK_MOVE'), ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>

</div>
