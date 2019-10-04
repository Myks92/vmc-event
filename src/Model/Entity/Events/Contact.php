<?php


namespace Myks92\Vmc\Event\Model\Entity\Events;


use DomainException;

/**
 * Class Contact
 * @author Maxim Vorozhtsov <myks1992@mail.ru>
 */
class Contact
{
    public const TYPE_EMAIL = 'email';
    public const TYPE_PHONE = 'phone';
    /**
     * @var string
     */
    private $type;
    /**
     * @var string
     */
    private $value;

    /**
     * Contact constructor.
     * @param string $type
     * @param string $value
     * @throws DomainException
     */
    public function __construct(string $type, string $value)
    {
        if (!in_array($type, array_keys(static::getTypes()))) {
            throw new DomainException('Тип не существует');
        }
        $this->type = $type;
        $this->value = $value;
    }

    /**
     * @return array
     */
    public static function getTypes(): array
    {
        return [
            self::TYPE_PHONE => 'Телефон',
            self::TYPE_EMAIL => 'Email',
        ];
    }

    /**
     * @param string $type
     * @param string $value
     * @return Contact
     * @throws DomainException
     */
    public static function create(string $type, string $value): self
    {
        return new static($type, $value);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return bool
     */
    public function isForValue(string $value): bool
    {
        return $this->value === $value;
    }
}