<?php

namespace Myks92\Vmc\Event\Security\Access\Events;


use Myks92\Vmc\Event\Model\Entity\Events\Id;
use Myks92\Vmc\Event\ReadModel\Events\EventFetcher;
use yii\base\InvalidParamException;
use yii\rbac\CheckAccessInterface;
use yii\web\IdentityInterface;

/**
 * Class EventChecker
 * @author Maxim Vorozhtsov <myks1992@mail.ru>
 */
class EventChecker
{
    /**
     * Роль управляющего
     * @var string
     */
    public const MANAGE_ROLE = 'admin';
    /**
     * @var CheckAccessInterface
     */
    private $access;
    /**
     * @var IdentityInterface
     */
    private $identity;
    /**
     * @var EventFetcher
     */
    private $fetcher;

    /**
     * MemberChecker constructor.
     * @param CheckAccessInterface $access
     * @param IdentityInterface $identity
     * @param EventFetcher $fetcher
     */
    public function __construct(CheckAccessInterface $access, EventFetcher $fetcher, ?IdentityInterface $identity)
    {
        $this->access = $access;
        $this->identity = $identity;
        $this->fetcher = $fetcher;
    }

    /**
     * @return bool
     */
    public function allowCreate(): bool
    {
        return $this->identity ? true : false;
    }

    /**
     * @return bool
     * @throws InvalidParamException
     */
    private function checkAccess(): bool
    {
        return $this->identity ? $this->access->checkAccess($this->identity->getId(), self::MANAGE_ROLE) : false;
    }

    /**
     * @param Id $id
     * @return bool
     * @throws InvalidParamException
     */
    public function allowEdit(Id $id): bool
    {
        return $this->checkAccess() || $this->checkOwner($id);
    }

    /**
     * @return bool
     * @throws InvalidParamException
     */
    public function allowChangeStatus(): bool
    {
        return $this->checkAccess();
    }

    /**
     * @return bool
     * @throws InvalidParamException
     */
    public function allowManager(): bool
    {
        return $this->checkAccess();
    }

    /**
     * @param Id $id
     * @return bool
     */
    private function checkOwner(Id $id): bool
    {
        if (($event = $this->fetcher->findId($id)) && $this->identity) {
            return $event->getOwnerId() === $this->identity->getId();
        }
        return false;
    }

    /**
     * @return bool
     * @throws InvalidParamException
     */
    public function allowRemove(): bool
    {
        return $this->checkAccess();
    }
}