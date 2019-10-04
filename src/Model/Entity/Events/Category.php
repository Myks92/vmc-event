<?php

namespace Myks92\Vmc\Event\Model\Entity\Events;


use DomainException;

/**
 * Class Category
 * @author Maxim Vorozhtsov <myks1992@mail.ru>
 */
class Category
{
    public const FESTIVAL = 1;
    public const COMPETITION = 2;
    public const MASTER_CLASS = 3;
    public const SEMINAR = 4;
    public const CONTEST = 5;
    public const COURSE = 6;
    /**
     * @var int
     */
    private $id;

    /**
     * Category constructor.
     * @param int $id
     * @throws DomainException
     */
    public function __construct(int $id)
    {
        if (!in_array($id, array_keys($this::names()))) {
            throw new DomainException('Категория не существует');
        }
        $this->id = $id;
    }

    /**
     * @return array
     */
    public static function names(): array
    {
        return [
            self::FESTIVAL => 'Фестиваль',
            self::COMPETITION => 'Соревнование',
            self::CONTEST => 'Конкурс',
            self::SEMINAR => 'Семинар',
            self::COURSE => 'Курс',
            self::MASTER_CLASS => 'Мастер-класс',
        ];
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}