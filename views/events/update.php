<?php

use Myks92\Vmc\Event\Model\Entity\Events\Event;
use unclead\multipleinput\TabularInput;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\widgets\MaskedInput;

/* @var yii\web\View $this */
/* @var Event $model */
/* @var \Myks92\Vmc\Event\Model\UseCase\Events\Edit\Form $editForm */

$this->title = Yii::t('event-event', 'TITLE_EVENT_UPDATE');
$this->params['breadcrumbs'][] = ['label' => Yii::t('event-event', 'TITLE_EVENTS'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->getName(), 'url' => ['view', 'id' => $model->getId()->getValue()]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-update">

    <div class="card shadow-sm mb-3">
        <div class="card-body">

            <div class="event-form">

                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($editForm, 'name')->textInput(['maxlength' => true]) ?>

                <?= $form->field($editForm, 'category')->dropDownList($editForm->getCategories()) ?>

                <label><?= Yii::t('event-event', 'TITLE_EVENT_URLS')?></label>

                <?= TabularInput::widget([
                    'models' => $editForm->urls,
                    'min' => 1,
                    'max' => 50,
                    'addButtonOptions' => [
                        'class' => 'btn btn-success',
                    ],
                    'columns' => [
                        [
                            'name' => 'type',
                            'type' => 'dropDownList',
                            'items' => $editForm->urls[0]->getTypes(),
                        ],
                        [
                            'name' => 'value',
                        ],
                    ]
                ]) ?>

                <label><?= Yii::t('event-event', 'TITLE_EVENT_CONTACTS')?></label>

                <?= TabularInput::widget([
                    'models' => $editForm->contacts,
                    'min' => 1,
                    'max' => 50,
                    'addButtonOptions' => [
                        'class' => 'btn btn-success',
                    ],
                    'columns' => [
                        [
                            'name' => 'type',
                            'type' => 'dropDownList',
                            'items' => $editForm->contacts[0]->getTypes(),
                        ],
                        [
                            'name' => 'value',
                        ],
                    ]
                ]) ?>

                <?= $form->field($editForm, 'description')->textarea(['rows' => 6]) ?>

                <div class="form-group">
                    <?= Html::submitButton(Yii::t('event-event', 'LINK_SAVE'), ['class' => 'btn btn-success']); ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>

</div>
