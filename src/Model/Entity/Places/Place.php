<?php


namespace Myks92\Vmc\Event\Model\Entity\Places;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\di\NotInstantiableException;

/**
 * This is the model class for table "{{%event_places}}".
 *
 * @property int $id
 * @property int $city_id Город
 * @property string $street Улица
 * @property string $name Название
 *
 * @property CityInterface $city
 */
class Place extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%event_places}}';
    }

    /**
     * @param string $name
     * @param int $city
     * @param string $street
     * @return Place
     */
    public static function create(string $name, int $city, string $street): self
    {
        $palace = new static();
        $palace->name = $name;
        $palace->city_id = $city;
        $palace->street = $street;

        return $palace;
    }

    /**
     * @param $id
     * @return bool
     */
    public function isIdEqualTo($id): bool
    {
        return $this->id === $id;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function isNameAndStreetEqualTo(string $name): bool
    {
        return  $this->getName() === $name;
    }

    /**
     * @return ActiveQuery
     * @throws InvalidConfigException
     * @throws NotInstantiableException
     */
    public function getCity(): ActiveQuery
    {
        return $this->hasOne(Yii::$container->get(CityInterface::class), ['id' => 'city_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['event_id', 'city_id', 'street', 'name'], 'required'],
            [['event_id', 'city_id'], 'integer'],
            [['street', 'name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('event-event-place', 'ID'),
            'event_id' => Yii::t('event-event-place', 'Event ID'),
            'city_id' => Yii::t('event-event-place', 'City ID'),
            'street' => Yii::t('event-event-place', 'Street'),
            'name' => Yii::t('event-event-place', 'Name'),
        ];
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getStreet(): string
    {
        return $this->street;
    }
}