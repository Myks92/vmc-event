<?php

use Myks92\Vmc\Event\Model\Entity\Events\Event;
use Myks92\Vmc\Event\Model\Entity\Places\Place;
use Myks92\Vmc\Event\Security\Access\Events\EventChecker;
use yii\helpers\Html;

/** @var Place $place */
/** @var Event $event */
/** @var EventChecker $checker */
?>

    <div class="list-group-item">
        <div class="event-place">
            <?php if ($checker->allowEdit($event->getId())): ?>
                <div class="float-right mt-2">
                    <?= Html::a('<i class="fas fa-trash"></i>', ['revoke-place'], [
                        'title' => Yii::t('event-event-place', 'LINK_REMOVE'),
                        'data' => [
                            'method' => 'post',
                            'confirm' => Yii::t('event-event-place', 'CONFIRM_DELETE'),
                            'params' => [
                                'event_id' => $event->getId()->getValue(),
                                'id' => $place->getId(),
                            ],
                        ],
                    ]) ?>
                </div>
            <?php endif; ?>
            <div class="event-place-info">
                <div><?= $place->getName() ?></div>
                <div><?= $place->city->getName() ?>, <?= $place->getStreet() ?></div>
                <div id="event-place-map-<?= $place->getId() ?>" style="width:100%; height:220px; margin-top: 10px"></div>
            </div>
        </div>
    </div>
<?php $this->registerJs("
    ymaps.ready(init".$place->getId().");
    function init".$place->getId()."(){
        ymaps.geocode('Россия, ".$place->city->getName().', '.$place->getStreet()."').then(function (res) {
            var coordinates = res.geoObjects.get(0).geometry.getCoordinates();
            var myMap".$place->getId()." = new ymaps.Map('event-place-map-".$place->getId()."', {
                center: coordinates,
                zoom : 15,
                controls: [
                    'zoomControl', // Ползунок масштаба
                    'fullscreenControl' // Полноэкранный режим
                ]
            });
            var myPlacemark".$place->getId()." = new ymaps.Placemark(coordinates, {}, {
                preset: 'islands#darkBlueDotIcon'
            });
            myMap".$place->getId().".geoObjects.add(myPlacemark".$place->getId().");
        });
    }");
?>