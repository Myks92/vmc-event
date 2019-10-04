<?php


namespace Myks92\Vmc\Event\Model\Entity\Events;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%event_event_places}}".
 *
 * @property int $id [int(11)]
 * @property int $event_id [int(11)]  Мероприятие
 * @property int $place_id [int(11)]  Место
 */
class PlaceAssignment extends ActiveRecord
{
    /**
     * @param int $id
     * @return PlaceAssignment
     */
    public static function create(int $id): self
    {
        $palace = new static();
        $palace->place_id = $id;
        return $palace;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%event_place_assignments}}';
    }

    /**
     * @param int $id
     * @return bool
     */
    public function isForPlace(int $id): bool
    {
        return $this->place_id === $id;
    }
}