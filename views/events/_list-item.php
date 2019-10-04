<?php

use Myks92\Vmc\Event\Model\Entity\Events\Event;
use Myks92\Vmc\Event\Security\Access\Events\EventChecker;
use Myks92\Vmc\Event\Widget\Events\Poster;
use yii\helpers\Html;

/** @var Event $model */
/** @var EventChecker $checker */
?>
<div class="event">
    <div class="row">
        <div class="col-4 col-sm-2">
            <?= Poster::widget([
                'image' => $model->getPoster(),
                'url' => ['view', 'id' => $model->getId()->getValue()],
            ]) ?>
        </div>
        <div class="col-8 col-sm-10">
            <h2 class="h5"><?= Html::a($model->getName(), ['view', 'id' => $model->getId()->getValue()],
                    ['class' => 'event-list-name']) ?></h2>
            <div class="text-black-50"><?= Yii::t('event-event-category', $model->getCategory()->getId()) ?></div>
            <div class="text-black-50 small">
                <?= Yii::t('event-event', 'Date From') ?> <?= $model->getDate()->getFrom()->format('d.m.Y') ?>
            </div>

            <?php if ($checker->allowManager()): ?>
                <div class="text-black-50 small">
                    <?= Yii::t('event-event', 'Status') ?>: <?= Yii::t('event-event-status', $model->getStatus()->getValue()) ?>
                </div>
            <?php endif ?>
        </div>
    </div>
</div>