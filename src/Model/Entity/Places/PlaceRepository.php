<?php

namespace Myks92\Vmc\Event\Model\Entity\Places;


use RuntimeException;
use Throwable;


/**
 * Class PlaceRepository
 * @author Maxim Vorozhtsov <myks1992@mail.ru>
 */
class PlaceRepository
{
    /**
     * @param int $id
     * @return Place
     * @throws RuntimeException
     */
    public function get(int $id): Place
    {
        if (!$place = Place::findOne($id)) {
            throw new RuntimeException('Model not found');
        }
        return $place;
    }

    /**
     * @param Place $place
     * @throws RuntimeException
     * @throws Throwable
     */
    public function add(Place $place): void
    {
        if (!$place->getIsNewRecord()) {
            throw new RuntimeException('Model not exists');
        }
        $place->insert(false);
    }

    /**
     * @param string $name
     * @param string $street
     * @param int $city
     * @return Place
     * @throws RuntimeException
     * @throws Throwable
     */
    public function findOrAddByNameAndStreetAndCity(string $name, string $street, int $city): Place
    {
        if(!($place = Place::findOne(['name' => $name, 'street' => $street, 'city_id' => $city]))) {
            $place = Place::create($name, $city, $street);
            $this->add($place);
        }
        return $place;
    }
}