<?php

use unclead\multipleinput\TabularInput;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\widgets\MaskedInput;

/* @var yii\web\View $this */
/* @var \Myks92\Vmc\Event\Model\UseCase\Events\Create\Form $createForm */

$this->title = Yii::t('event-event', 'TITLE_EVENT_CREATE');
$this->params['breadcrumbs'][] = ['label' => Yii::t('event-event', 'TITLE_EVENTS'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-create">

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <div class="event-form">

                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($createForm, 'name')->textInput(['maxlength' => true]) ?>

                <?= $form->field($createForm, 'category')->dropDownList($createForm->getCategories()) ?>

                <?= $form->field($createForm->date, 'from')->widget(MaskedInput::class, [
                    'mask' => '99.99.9999'
                ]) ?>

                <?= $form->field($createForm->date, 'to')->widget(MaskedInput::class, [
                    'mask' => '99.99.9999'
                ]) ?>

                <label><?= Yii::t('event-event', 'TITLE_EVENT_URLS')?></label>

                <?= TabularInput::widget([
                    'models' => $createForm->urls,
                    'min' => 1,
                    'max' => 50,
                    'addButtonOptions' => [
                        'class' => 'btn btn-success',
                    ],
                    'columns' => [
                        [
                            'name' => 'type',
                            'type' => 'dropDownList',
                            'items' => $createForm->urls[0]->getTypes(),
                        ],
                        [
                            'name' => 'value',
                        ],
                    ]
                ]) ?>

                <label><?= Yii::t('event-event', 'TITLE_EVENT_CONTACTS')?></label>

                <?= TabularInput::widget([
                    'models' => $createForm->contacts,
                    'min' => 1,
                    'max' => 50,
                    'addButtonOptions' => [
                        'class' => 'btn btn-success',
                    ],
                    'columns' => [
                        [
                            'name' => 'type',
                            'type' => 'dropDownList',
                            'items' => $createForm->contacts[0]->getTypes(),
                        ],
                        [
                            'name' => 'value',
                        ],
                    ]
                ]) ?>

                <?= $form->field($createForm, 'description')->textarea(['rows' => 6]) ?>

                <div class="form-group">
                    <?= Html::submitButton(Yii::t('event-event', 'LINK_SAVE'), ['class' => 'btn btn-success']); ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>

</div>
