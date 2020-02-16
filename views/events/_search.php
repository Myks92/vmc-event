<?php

use Myks92\Vmc\Event\ReadModel\Events\Filter;
use Myks92\Vmc\Event\Security\Access\Events\EventChecker;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Url;
use yii\widgets\MaskedInput;

/* @var yii\web\View $this */
/* @var Filter $model */
/* @var ActiveForm $form */
/* @var EventChecker $checker */
?>

<div class="event-search">

    <?php $form = ActiveForm::begin([
        'action' => Url::to(['index']),
        'method' => 'get',
        'options' => [
            'class' => 'form-ajax',
            'data-id-content' => 'search-content'
        ],
    ]); ?>

    <?= $form->field($model, 'category')->dropDownList($model->getCategories()) ?>

    <?php if ($checker->allowManager()): ?>
        <?= $form->field($model, 'status')->dropDownList($model->getStatuses(), ['prompt' => '---']) ?>
    <?php endif; ?>

    <?php ActiveForm::end(); ?>

</div>
