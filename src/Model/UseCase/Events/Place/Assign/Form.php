<?php


namespace Myks92\Vmc\Event\Model\UseCase\Events\Place\Assign;


use Yii;
use yii\base\Model;

/**
 * Class Form
 * @author Maxim Vorozhtsov <myks1992@mail.ru>
 */
class Form extends Model
{
    public $name;
    public $street;
    public $city;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'street', 'city'], 'required'],
            [['name', 'street'], 'string', 'max' => 255],
            [['city'], 'integer'],
            [['name', 'street'], 'trim'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'name' => Yii::t('event-event-place', 'Name'),
            'street' => Yii::t('event-event-place', 'Street'),
            'city' => Yii::t('event-event-place', 'City ID'),
        ];
    }
}