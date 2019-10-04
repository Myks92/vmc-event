<?php

use app\modules\geo\models\GeoCity;
use Myks92\Vmc\Event\Model\Entity\Places\CityInterface;
use Myks92\Vmc\Event\Model\EventDispatcherInterface;
use Myks92\Vmc\Event\Module;
use Myks92\Vmc\Event\Service\Uploader\FileUploader;
use yii\di\Container;
use yii\rbac\CheckAccessInterface;
use yii\rbac\ManagerInterface;
use yii\web\IdentityInterface;

return [
    'aliases' => [
        '@staticRoot' => $params['staticPath'],
        '@static' => $params['staticHostInfo'],
        '@event' => '@vendor/myks92/vmc-event',
    ],
    'modules' => [
        'events' => [
            'class' => Module::class,
            'viewPath' => '@event/views',
        ],
    ],
    'components' => [
        'i18n' => [
            'translations' => [
                'event' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'forceTranslation' => true,
                    'basePath' => '@event/messages',
                ],
                'event-event' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'forceTranslation' => true,
                    'basePath' => '@event/messages',
                ],
                'event-event-place' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'forceTranslation' => true,
                    'basePath' => '@event/messages',
                ],
                'event-event-category' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'forceTranslation' => true,
                    'basePath' => '@event/messages',
                ],
                'event-event-status' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'forceTranslation' => true,
                    'basePath' => '@event/messages',
                ],
                'event-event-contact' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'forceTranslation' => true,
                    'basePath' => '@event/messages',
                ],
                'event-event-url' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'forceTranslation' => true,
                    'basePath' => '@event/messages',
                ],
            ],
        ],
    ],
    'container' => [
        'singletons' => [
            EventDispatcherInterface::class => static function (Container $container) {
                return new Myks92\Vmc\Event\Event\Dispatcher\SimpleEventDispatcher($container, []);
            },
            CheckAccessInterface::class => ManagerInterface::class,
            IdentityInterface::class => function () {
                return Yii::$app->user->getIdentity();
            },
            FileUploader::class => static function () {
                return new Myks92\Vmc\Event\Service\Uploader\FileUploader(Yii::getAlias('@staticRoot/origin/images/events'));
            },
            CityInterface::class => GeoCity::class
        ],
    ],
    'params' => require(__DIR__ . '/params.php')
];