<?php


namespace Myks92\Vmc\Event\Model\Entity\Events;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Event]].
 *
 * @see Event
 */
class EventQuery extends ActiveQuery
{
    /**
     * @param $ownerId
     * @return EventQuery
     */
    public function byOwner($ownerId)
    {
        return $this->andWhere('owner_id = :owner_id', [':owner_id' => $ownerId]);
    }

    /**
     * @return EventQuery
     */
    public function active()
    {
        return $this->andWhere('status = :status', [':status' => Status::ACTIVE]);
    }

    /**
     * {@inheritdoc}
     * @return Event[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Event|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}