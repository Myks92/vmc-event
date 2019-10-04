<?php


namespace Myks92\Vmc\Event\Model\Entity\Events;


use DomainException;

/**
 * Class Url
 * @author Maxim Vorozhtsov <myks1992@mail.ru>
 */
class Url
{
    public const TYPE_WEB = 'web';
    public const TYPE_VK = 'vk';
    public const TYPE_INSTAGRAM = 'instagram';
    public const TYPE_REGISTRATION = 'registration';
    public const TYPE_REGULATION = 'regulation';

    /**
     * @var string
     */
    private $type;
    /**
     * @var string
     */
    private $value;

    /**
     * Url constructor.
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
            self::TYPE_WEB => 'Веб сайт',
            self::TYPE_REGULATION => 'Положение',
            self::TYPE_REGISTRATION => 'Регистрация',
            self::TYPE_VK => 'Вконтакте',
            self::TYPE_INSTAGRAM => 'Инстаграм',
        ];
    }

    /**
     * @param string $type
     * @param string $value
     * @return Url
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