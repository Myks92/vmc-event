<?php

namespace Myks92\Vmc\Event\Widget;


use yii\helpers\ArrayHelper;
use yii\helpers\Html;


/**
 * Class Menu
 * @author Maxim Vorozhtsov <myks1992@mail.ru>
 */
class Menu extends \yii\widgets\Menu
{
    public $encodeLabels = false;
    public $options = ['class' => 'list-group'];
    public $itemOptions = ['class' => 'list-group-item text-black-50'];
    public $linkOptions;
    public $linksOptions = ['class' => 'text-black-50'];

    /**
     * @param array $item
     * @return string
     */
    protected function renderItem($item)
    {
        if (isset($item['url'])) {
            $icon = isset($item['icon']) ? $item['icon'] : '';
            $icon = '<span style="width: 24px;height: 24px;margin: 6px 10px 0 0; font-size: 14px;">' . $icon . '</span>';
            $text = $icon . $item['label'];

            return Html::a(
                $text,
                $item['url'],
                ArrayHelper::merge(
                    ArrayHelper::getValue($item, 'linksOptions', $this->linksOptions),
                    ArrayHelper::getValue($item, 'linkOptions', [])
                )
            );
        }

        $template = ArrayHelper::getValue($item, 'template', $this->labelTemplate);

        return strtr($template, [
            '{label}' => $item['label'],
        ]);
    }
}