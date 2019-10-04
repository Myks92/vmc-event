<?php

use Myks92\Vmc\Event\Assets\EventAssets;
use Myks92\Vmc\Event\Model\Entity\Events\Event;
use Myks92\Vmc\Event\Widget\Events\Poster;
use yii\helpers\Html;
use yii\helpers\StringHelper;

/** @var Event[] $events */

EventAssets::register($this);
?>

<div class="events-last-widgets">
    <div class="row">
        <?php foreach ($events as $event): ?>
            <div class="col-sm-3">
                <div class="card mb-4 shadow-sm">
                    <?= Poster::widget([
                        'image' => $event->getPoster(),
                        'imageOptions' => ['class' => 'card-img-top'],
                        'url' => ['/events/events/view', 'id' => $event->getId()]
                    ]) ?>
                    <div class="card-body">
                        <h3 class="h5"><?= StringHelper::truncate($event->getName(), 90, '...') ?></h3>
                        <p class="card-text"><?= Yii::t('event-event-category', $event->getCategory()->getId()) ?></p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="btn-group">
                                <?= Html::a('Просмотр', ['/events/events/view', 'id' => $event->getId()], [
                                    'class' => 'btn btn-sm btn-outline-secondary'
                                ]) ?>
                            </div>
                            <small class="text-muted"><?= $event->getDate()->getFrom()->format('d.m.Y') ?></small>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
