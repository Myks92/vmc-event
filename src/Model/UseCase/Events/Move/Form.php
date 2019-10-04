<?php


namespace Myks92\Vmc\Event\Model\UseCase\Events\Move;


use DateTimeImmutable;
use Exception;
use Myks92\Vmc\Event\Model\Entity\Events\Event;
use Yii;
use yii\base\Model;

/**
 * Class Form
 * @author Maxim Vorozhtsov <myks1992@mail.ru>
 */
class Form extends Model
{
    public $from;
    public $to;

    /**
     * Form constructor.
     * @param Event|null $event
     * @param array $config
     * @throws Exception
     */
    public function __construct(Event $event = null, $config = [])
    {
        if ($event) {
            $this->from = $event->getDate()->getFrom()->format('d.m.Y');
            $this->to = $event->getDate()->getTo()->format('d.m.Y');
        }
        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['from', 'to'], 'required'],
            [['from', 'to'], 'date', 'format' => 'php:d.m.Y'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'from' => Yii::t('event-event', 'Date From'),
            'to' => Yii::t('event-event', 'Date To'),
        ];
    }
}