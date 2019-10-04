<?php


namespace Myks92\Vmc\Event\Model\Entity\Events;


use DomainException;

/**
 * Class Status
 * @author Maxim Vorozhtsov <myks1992@mail.ru>
 */
class Status
{
    public const NEW = 'new';
    public const ACTIVE = 'active';
    public const CANCELLED = 'cancelled';
    public const REJECTED = 'rejected';
    /**
     * @var string
     */
    private $value;

    /**
     * Status constructor.
     * @param string $value
     * @throws DomainException
     */
    public function __construct(string $value)
    {
        if (!in_array($value, array_keys($this::names()))) {
            throw new DomainException('Статус не существует');
        }
        $this->value = $value;
    }

    /**
     * @return array
     */
    public static function names(): array
    {
        return [
            self::NEW => 'Новый',
            self::ACTIVE => 'Принятый',
            self::REJECTED => 'Отклонён',
            self::CANCELLED => 'Отменён',
        ];
    }

    /**
     * @return Status
     * @throws DomainException
     */
    public static function new(): self
    {
        return new self(self::NEW);
    }

    /**
     * @return Status
     * @throws DomainException
     */
    public static function activate(): self
    {
        return new self(self::ACTIVE);
    }

    /**
     * @return Status
     * @throws DomainException
     */
    public static function rejected(): self
    {
        return new self(self::REJECTED);
    }

    /**
     * @return Status
     * @throws DomainException
     */
    public static function cancelled(): self
    {
        return new self(self::CANCELLED);
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return bool
     */
    public function isNew(): bool
    {
        return $this->value === self::NEW;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->value === self::ACTIVE;
    }

    /**
     * @return bool
     */
    public function isRejected(): bool
    {
        return $this->value === self::REJECTED;
    }

    /**
     * @return bool
     */
    public function isCancelled(): bool
    {
        return $this->value === self::CANCELLED;
    }
}