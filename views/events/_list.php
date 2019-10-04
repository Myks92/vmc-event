<?php

use Myks92\Vmc\Event\Security\Access\Events\EventChecker;
use yii\data\ActiveDataProvider;
use yii\widgets\ListView;

/** @var ActiveDataProvider $dataProvider */
/** @var EventChecker $checker */
?>


<?=
ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView' => '_list-item',
    'viewParams' => [
        'checker' => $checker
    ]
]) ?>
