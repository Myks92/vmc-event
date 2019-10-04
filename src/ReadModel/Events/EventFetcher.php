<?php


namespace Myks92\Vmc\Event\ReadModel\Events;


use DateTimeImmutable;
use Myks92\Vmc\Event\Model\Entity\Events\Event;
use Myks92\Vmc\Event\Model\Entity\Events\Id;

/**
 * Class EventFetcher
 * @author Maxim Vorozhtsov <myks1992@mail.ru>
 */
class EventFetcher
{
    /**
     * @param Id $id
     * @return Event|null
     */
    public function findId(Id $id): ?Event
    {
        return Event::findOne($id->getValue());
    }

    public function findLastActive(int $limit): array
    {
        return Event::find()
            ->limit($limit)
            ->orderBy('date_from ASC')
            ->andFilterWhere(['>=', 'date_to', (new DateTimeImmutable())->format('Y-m-d')])
            ->active()
            ->all();
    }
}