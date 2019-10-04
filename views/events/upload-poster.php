<?php

use kartik\form\ActiveForm;
use maxdancepro\image\Widget;
use Myks92\Vmc\Event\Model\Entity\Events\Event;
use yii\helpers\Html;

/* @var yii\web\View $this */
/* @var \Myks92\Vmc\Event\Model\UseCase\Events\Poster\Upload\Form $posterForm */
/* @var Event $model */

$this->title = Yii::t('event-event', 'TITLE_EVENT_UPLOAD_POSTER');
$this->params['breadcrumbs'][] = ['label' => Yii::t('event-event', 'TITLE_EVENTS'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->getName(), 'url' => ['view', 'id' => $model->getId()->getValue()]];
$this->params['breadcrumbs'][] = $this->title
?>
<div class="competition-edit-poster">

    <div class="card shadow-sm mb-3">
        <h4 class="card-header">
            <?= Html::encode($this->title) ?>
        </h4>
        <div class="card-body">
            <?php $form = ActiveForm::begin([
                'options' => ['enctype' => 'multipart/form-data']
            ]); ?>

            <?= $form->field($posterForm, 'poster')->fileInput() ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('event-event', 'LINK_UPLOAD'), ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>