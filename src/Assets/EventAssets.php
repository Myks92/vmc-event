<?php


namespace Myks92\Vmc\Event\Assets;


use yii\web\AssetBundle;

/**
 * Class EventAssets
 * @author Maxim Vorozhtsov <myks1992@mail.ru>
 */
class EventAssets extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@event/public/assets';
    /**
     * @var array
     */
    public $css = [
        'css/event.css'
    ];
}