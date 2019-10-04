<?php

namespace Myks92\Vmc\Event\Widget\Events;

use yii\base\Widget;
use yii\helpers\Html;

/**
 * Class Poster
 * @author Maxim Vorozhtsov <myks1992@mail.ru>
 */
class Poster extends Widget
{
    /**
     * @var array Адрес, по которому можно перейти нажав на картинку.
     * По умолчанию стоит null. Вводить необходимо ['default/view', 'id' => $model->id]
     */
    public $url;
    /**
     * @var string, Размер изображения в пискселях.
     */
    public $size = false;
    /**
     * @var array Опции для тега image
     */
    public $imageOptions = [];
    /**
     * @var array, Опции для тега ссылки
     */
    public $urlOptions;
    /**
     * @var string, путь
     */
    public $path = '@static/origin/images/events';
    /**
     * @var string, изображение
     */
    public $image;
    /**
     * @var string, Адрес изображения по умолчанию
     */
    public $defaultImageUrl = '@web/images/default_img.jpg';

    /**
     *{@inheritdoc}
     */
    public function init()
    {
        Html::addCssClass($this->imageOptions, 'event-avatar-img img-fluid');
        Html::addCssClass($this->urlOptions, 'event-avatar-url');
        Html::addCssStyle($this->imageOptions, 'overflow: hidden;');
        Html::addCssStyle($this->imageOptions, 'width:' . $this->size . 'px;');
        Html::addCssStyle($this->imageOptions, 'height:' . $this->size . 'px;');
    }

    /**
     * @return string
     */
    public function run(): string
    {
        $src = $this->getImageUrl();
        if (!$this->url) {
            $this->url = $src;
        }
        return Html::a(Html::img($src, $this->imageOptions), $this->url, $this->urlOptions);
    }

    /**
     * @return string
     */
    protected function getImageUrl(): string
    {
        if (!empty($image = $this->image)) {
            return $this->path . DIRECTORY_SEPARATOR . $image;
        }
        return $this->defaultImageUrl;
    }

}