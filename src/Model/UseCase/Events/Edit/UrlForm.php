<?php


namespace Myks92\Vmc\Event\Model\UseCase\Events\Edit;

use Myks92\Vmc\Event\Model\Entity\Events\Url;
use Yii;
use yii\base\Model;

/**
 * Class UrlForm
 * @author Maxim Vorozhtsov <myks1992@mail.ru>
 */
class UrlForm extends Model
{
    public $type;
    public $value;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['type'], 'string', 'max' => 50],
            [['value'], 'string', 'max' => 255],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'type' => Yii::t('event-event', 'URL_TYPE'),
            'value' => Yii::t('event-event', 'URL_VALUE'),
        ];
    }

    /**
     * @return array
     */
    public function getTypes(): array
    {
        return Url::getTypes();
    }
}