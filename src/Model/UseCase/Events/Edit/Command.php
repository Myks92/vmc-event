<?php


namespace Myks92\Vmc\Event\Model\UseCase\Events\Edit;


/**
 * Class Command
 * @author Maxim Vorozhtsov <myks1992@mail.ru>
 */
class Command
{
    /**
     * @var
     */
    public $id;
    /**
     * @var string
     */
    public $name;
    /**
     * @var int
     */
    public $category;
    /**
     * @var string
     */
    public $dateFrom;
    /**
     * @var string
     */
    public $dateTo;
    /**
     * @var ContactRow[]
     */
    public $contacts;
    /**
     * @var UrlRow[]
     */
    public $urls;
    /**
     * @var string|null
     */
    public $description;
    /**
     * @var int|null
     */
    public $owner = null;
}