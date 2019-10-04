<?php


namespace Myks92\Vmc\Event\Model\UseCase\Events\Edit;


/**
 * Class UrlRow
 * @author Maxim Vorozhtsov <myks1992@mail.ru>
 */
class UrlRow
{
    /**
     * @var string
     */
    public $type;
    /**
     * @var string
     */
    public $value;

    /**
     * UrlRow constructor.
     * @param string $type
     * @param string $value
     */
    public function __construct(string $type, string $value)
    {
        $this->type = $type;
        $this->value = $value;
    }
}