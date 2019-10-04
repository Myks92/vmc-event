<?php

use Myks92\Vmc\Event\Model\Entity\Events\Contact;
use Myks92\Vmc\Event\Model\Entity\Events\Event;
use Myks92\Vmc\Event\Model\Entity\Events\Url;
use Myks92\Vmc\Event\Security\Access\Events\EventChecker;
use Myks92\Vmc\Event\Widget\Events\Poster;
use Myks92\Vmc\Event\Widget\Menu;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var yii\web\View $this */
/* @var Event $model */
/* @var EventChecker $checker */

$this->title = $model->getName();
$this->params['breadcrumbs'][] = ['label' => Yii::t('event-event', 'TITLE_EVENTS'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-view">

    <div class="row">
        <div class="col-sm-8 col-md-9 order-12 order-sm-0">

            <?php if ($model->getStatus()->isCancelled()): ?>
                <div class="alert alert-warning" role="alert">
                    <strong class="h4">Мероприятие отменено!</strong><br>
                    <blockquote><?= $model->getCancelReason() ?></blockquote>
                </div>
            <?php endif ?>

            <?php if ($model->getStatus()->isNew()): ?>
                <div class="alert alert-warning" role="alert">
                    <strong class="h4">Мероприятие не активировано!</strong><br>
                    <blockquote>Текущее мероприятие ещё не активировано и не отображается в общем списке!</blockquote>
                </div>
            <?php endif ?>

            <div class="card shadow-sm mb-3">
                <div class="card-header"><?= $this->title ?></div>
                <div class="card-table">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            [
                                'attribute' => 'category_id',
                                'value' => Yii::t('event-event-category', $model->getCategory()->getId()),
                            ],
                            'date_from:date',
                            'date_to:date',
                            [
                                'attribute' => 'status',
                                'value' => Yii::t('event-event-status', $model->getStatus()->getValue()),
                                'visible' => $checker->allowManager(),
                            ],
                            'created_at:datetime',
                            'updated_at:datetime',
                        ],
                    ]) ?>
                </div>
                <div class="card-footer">
                    <span class="float-right text-black-50"><i class="fas fa-eye"></i> <?= $model->getViewCount() ?></span>
                </div>
            </div>

            <div class="card shadow-sm mb-3">
                <div class="card-header">
                    <div class="float-left"><?= Yii::t('event-event', 'TITLE_EVENT_PLACES') ?></div>
                    <?php if ($checker->allowEdit($model->getId())): ?>
                        <div class="float-right">
                            <?= Html::a(Yii::t('event-event-place', 'LINK_CREATE'), [
                                'assign-place',
                                'event_id' => $model->getId()->getValue(),
                            ]) ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="list-group list-group-flush">
                    <?php if ($model->places): ?>
                        <?php foreach ($model->places as $place): ?>
                            <?= $this->render('_place', [
                                'place' => $place,
                                'event' => $model,
                                'checker' => $checker,
                            ]) ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="list-group-item"><?= Yii::$app->formatter->nullDisplay ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card shadow-sm mb-3">
                <div class="card-header"><?= Yii::t('event-event', 'Description') ?></div>
                <div class="card-body">
                    <?php if ($model->getDescription()): ?>
                        <?= Yii::$app->formatter->asHtml($model->getDescription(), [
                            'Attr.AllowedRel' => ['nofollow'],
                            'HTML.SafeObject' => true,
                            'Output.FlashCompat' => true,
                            'HTML.SafeIframe' => true,
                            'AutoFormat.AutoParagraph' => true,
                            'AutoFormat.RemoveEmpty' => true,
                            'AutoFormat.Linkify' => true,
                            'AutoFormat.DisplayLinkURI' => true,
                            'HTML.TargetBlank' => true,
                            'URI.SafeIframeRegexp' => '%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%',
                        ]) ?>
                    <?php else: ?>
                        <?= Yii::$app->formatter->nullDisplay ?>
                    <?php endif; ?>
                </div>
            </div>

        </div>
        <div class="col-sm-4 col-md-3 order-1 order-sm-0">
            <div class="card shadow-sm mb-3">
                <div class="card-img-wrap">
                    <?= Poster::widget([
                        'image' => $model->getPoster(),
                        'imageOptions' => ['class' => 'card-img-top'],
                    ]) ?>
                    <?php if ($checker->allowEdit($model->getId())): ?>
                        <div class="card-img-controls">
                            <?= Html::a('<i class="fas fa-upload"></i>', ['upload-poster', 'id' => $model->getId()->getValue()], [
                                'title' => Yii::t('event-event', 'LINK_UPLOAD_POSTER'),
                            ]); ?>
                            <?php if ($model->getPoster()): ?>
                                <?= Html::a('<i class="fas fa-trash"></i>', ['remove-poster', 'id' => $model->getId()->getValue()], [
                                    'title' => Yii::t('event-event', 'LINK_REMOVE_POSTER'),
                                ]) ?>
                            <?php endif ?>
                        </div>
                    <?php endif ?>
                </div>
            </div>

            <div class="shadow-sm mb-3">
                <?= Menu::widget([
                    'items' => [
                        [
                            'label' => Yii::t('event-event', 'LINK_STATUS_ACTIVE'),
                            'url' => ['activate', 'id' => $model->getId()->getValue()],
                            'linkOptions' => [
                                'data-method' => 'post',
                                'class' => 'text-success',
                            ],
                            'icon' => '<i class="fas fa-check-circle"></i>',
                            'visible' => $checker->allowChangeStatus() && !$model->getStatus()->isActive(),
                        ],
                        [
                            'label' => Yii::t('event-event', 'LINK_EDIT'),
                            'url' => ['update', 'id' => $model->getId()->getValue()],
                            'icon' => '<i class="fas fa-pencil-alt"></i>',
                            'visible' => $checker->allowEdit($model->getId()),
                        ],
                        [
                            'label' => Yii::t('event-event', 'LINK_UPLOAD_POSTER'),
                            'url' => ['upload-poster', 'id' => $model->getId()->getValue()],
                            'icon' => '<i class="fas fa-upload"></i>',
                            'visible' => $checker->allowEdit($model->getId()),
                        ],
                        [
                            'label' => Yii::t('event-event', 'LINK_REMOVE_POSTER'),
                            'url' => ['remove-poster', 'id' => $model->getId()->getValue()],
                            'icon' => '<i class="fas fa-trash"></i>',
                            'visible' => $checker->allowEdit($model->getId()) && $model->getPoster(),
                        ],
                        [
                            'label' => Yii::t('event-event', 'LINK_MOVE'),
                            'url' => ['move', 'id' => $model->getId()->getValue()],
                            'icon' => '<i class="fas fa-history"></i>',
                            'visible' => $checker->allowEdit($model->getId()) && !$model->getStatus()->isCancelled(),
                        ],
                        [
                            'label' => Yii::t('event-event', 'LINK_STATUS_REJECT'),
                            'url' => ['reject', 'id' => $model->getId()->getValue()],
                            'linkOptions' => [
                                'data-confirm' => Yii::t('event-event', 'CONFIRM_REJECT'),
                                'data-method' => 'post',
                            ],
                            'visible' => $checker->allowChangeStatus() && $model->getStatus()->isNew(),
                            'icon' => '<i class="fas fa-pause-circle"></i>',
                        ],
                        [
                            'label' => Yii::t('event-event', 'LINK_STATUS_CANCEL'),
                            'url' => ['cancel', 'id' => $model->getId()->getValue()],
                            'linkOptions' => [
                                'data-confirm' => Yii::t('event-event', 'CONFIRM_CANCEL'),
                                'data-method' => 'post',
                            ],
                            'visible' => $checker->allowChangeStatus() && $model->getStatus()->isActive(),
                            'icon' => '<i class="fas fa-ban"></i>',
                        ],
                        [
                            'label' => Yii::t('event-event', 'LINK_DELETE'),
                            'url' => ['delete', 'id' => $model->getId()->getValue()],
                            'linkOptions' => [
                                'data-confirm' => Yii::t('event-event', 'CONFIRM_DELETE'),
                                'data-method' => 'post',
                            ],
                            'icon' => '<i class="fas fa-trash"></i>',
                            'visible' => $checker->allowRemove(),
                        ],
                    ],
                ]); ?>
            </div>

            <div class="card shadow-sm mb-3">
                <div class="card-header">
                    <div class="float-left"><?= Yii::t('event-event', 'TITLE_EVENT_URLS') ?></div>
                </div>
                <?php if ($model->getContacts()): ?>
                    <?= Menu::widget([
                        'linksOptions' => false,
                        'items' => array_map(function (Url $url) {
                            return [
                                'label' => Yii::t('event-event-url', $url->getType()),
                                'url' => $url->getValue(),
                            ];
                        }, $model->getUrls()),
                    ]); ?>
                <?php else: ?>
                    <div class="card-body"><?= Yii::$app->formatter->nullDisplay ?></div>
                <?php endif; ?>
            </div>

            <div class="card shadow-sm mb-3">
                <div class="card-header">
                    <div class="float-left"><?= Yii::t('event-event', 'TITLE_EVENT_CONTACTS') ?></div>
                </div>
                <?php if ($model->getContacts()): ?>
                    <?= Menu::widget([
                        'linksOptions' => false,
                        'items' => array_map(function (Contact $contact) {
                            return [
                                'label' => $contact->getValue(),
                            ];
                        }, $model->getContacts()),
                    ]); ?>
                <?php else: ?>
                    <div class="card-body"><?= Yii::$app->formatter->nullDisplay ?></div>
                <?php endif; ?>
            </div>

        </div>
    </div>

</div>
