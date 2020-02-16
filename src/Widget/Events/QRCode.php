<?php

namespace Myks92\Vmc\Event\Widget\Events;

use yii\base\Widget;
use yii\helpers\Html;

/**
 * Class QRCode
 * @author Maxim Vorozhtsov <myks1992@mail.ru>
 */
class QRCode extends Widget
{
    /**
     * @var array Адрес, по которому можно перейти нажав на картинку.
     * По умолчанию стоит null. Вводить необходимо ['default/view', 'id' => $model->id]
     */
    public $url;
    /**
     * @var array Опции для тега image
     */
    public $imageOptions = [];
    /**
     * @var array Опции для ссыки image
     */
    public $options = [];
    /**
     * @var string, путь
     */
    public $path = '@static/origin/images/events/qr';
    /**
     * @var string, изображение
     */
    public $name;

    /**
     *{@inheritdoc}
     */
    public function init()
    {
        Html::addCssClass($this->imageOptions, 'event-qr-file img-fluid');
    }

    /**
     * @return string
     */
    public function run(): string
    {
        $fileName = $this->path . '/' . $this->name;
        if (!$this->url) {
            $this->url = $fileName;
        }
        return Html::a(Html::img($fileName, $this->imageOptions), $this->url, $this->options);
    }
}