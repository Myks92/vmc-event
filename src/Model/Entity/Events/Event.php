<?php


namespace Myks92\Vmc\Event\Model\Entity\Events;

use DateTimeImmutable;
use DomainException;
use Exception;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use Myks92\Vmc\Event\Model\AggregateRoot;
use Myks92\Vmc\Event\Model\Entity\Places\Place;
use Myks92\Vmc\Event\Model\EventTrait;
use Yii;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\di\NotInstantiableException;
use yii\helpers\Json;

/**
 * This is the model class for table "{{%event_events}}".
 *
 * @property int $id [int(11)]
 * @property string $poster [varchar(100)]  Афиша
 * @property string $name [varchar(255)]  Название
 * @property int $category_id [smallint(6)]  Категория
 * @property string $date_from [date]  Дата начала
 * @property string $date_to [date]  Дата окончания
 * @property string $status [varchar(20)]  Статус
 * @property string $cancel_reason [varchar(255)]  Причина отмены
 * @property string $contacts_json [json]
 * @property string $urls_json [json]
 * @property string $description
 * @property int $view_count [int(11)]  Колличество просмотров
 * @property int $owner_id [int(11)]
 * @property int $created_at [int(11)]
 * @property int $updated_at [int(11)]
 *
 * @property Place[] $places
 * @property PlaceAssignment[] $placeAssignments
 * @property OwnerInterface $owner
 */
class Event extends ActiveRecord implements AggregateRoot
{
    use EventTrait;

    /**
     * @var Contact[]
     */
    private $contacts = [];
    /**
     * @var Url[]
     */
    private $urls = [];

    /**
     * @param string $name
     * @param Category $category
     * @param Date $date
     * @param string|null $description
     * @param null $owner
     * @return Event
     * @throws DomainException
     */
    public static function create(string $name, Category $category, Date $date, string $description = null, $owner = null): self
    {
        $event = new static();
        $event->name = $name;
        $event->category_id = $category->getId();
        $event->date_from = $date->getFrom()->format('Y-m-d');
        $event->date_to = $date->getTo()->format('Y-m-d');
        $event->description = $description;
        $event->owner_id = $owner;
        $event->created_at = time();
        $event->status = Status::new()->getValue();

        return $event;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%event_events}}';
    }

    /**
     * {@inheritdoc}
     * @return EventQuery
     */
    public static function find(): EventQuery
    {
        return new EventQuery(get_called_class());
    }

    /**
     * @param string $name
     * @param Category $category
     * @param string|null $description
     */
    public function edit(string $name, Category $category, ?string $description): void
    {
        $event = $this;
        $event->name = $name;
        $event->category_id = $category->getId();
        $event->description = $description;
        $event->updated_at = time();
    }

    /**
     * @param string $poster
     */
    public function addPoster(string $poster): void
    {
        $this->poster = $poster;
    }

    public function removePoster(): void
    {
        $this->poster = null;
    }

    /**
     * @throws DomainException
     */
    public function activate(): void
    {
        if ($this->getStatus()->isActive()) {
            throw new DomainException('Мероприятие уже активировано!');
        }
        $this->cancel_reason = null;
        $this->status = Status::activate()->getValue();
    }

    /**
     * @return Status
     * @throws DomainException
     */
    public function getStatus(): Status
    {
        return new Status($this->status);
    }

    /**
     * @throws DomainException
     */
    public function reject(): void
    {
        if ($this->getStatus()->isRejected()) {
            throw new DomainException('Мероприятие уже отклонено!');
        }
        $this->cancel_reason = null;
        $this->status = Status::rejected()->getValue();
    }

    /**
     * @param string $reason
     * @throws DomainException
     */
    public function cancel(string $reason): void
    {
        if (!$this->isCanBeCancelled()) {
            throw new DomainException('Мероприятие не может быть отменено!');
        }
        $this->cancel_reason = $reason;
        $this->status = Status::cancelled()->getValue();
    }

    /**
     * @return bool
     * @throws DomainException
     */
    private function isCanBeCancelled(): bool
    {
        return $this->getStatus()->isActive() || !$this->getStatus()->isCancelled();
    }

    //Places

    /**
     * @param Date $date
     * @throws DomainException
     */
    public function moveToDates(Date $date): void
    {
        if (!$this->isCanBeMovedForDates($date)) {
            throw new DomainException('Мероприятие не может быть перенесено!');
        }
        $this->date_from = $date->getFrom()->format('Y-m-d');
        $this->date_to = $date->getTo()->format('Y-m-d');
        $this->updated_at = time();
    }

    /**
     * @param Date $date
     * @return bool
     * @throws DomainException
     */
    private function isCanBeMovedForDates(Date $date): bool
    {
        if ($date->getFrom()->getTimestamp() < (new DateTimeImmutable())->getTimestamp()) {
            throw new DomainException('Дата начала должна быть больше текущей даты!');
        }
        if ($date->getTo()->getTimestamp() < $date->getFrom()->getTimestamp()) {
            throw new DomainException('Дата окончания должна быть больше даты начала!');
        }
        return $this->getStatus()->isActive();
    }

    public function view(): void
    {
        $this->view_count++;
    }

    // Places

    /**
     * @param $id
     */
    public function assignPlace($id): void
    {
        $assignments = $this->placeAssignments;
        foreach ($assignments as $assignment) {
            if ($assignment->isForPlace($id)) {
                return;
            }
        }
        $assignments[] = PlaceAssignment::create($id);
        $this->placeAssignments = $assignments;
    }

    /**
     * @param $id
     * @throws DomainException
     */
    public function revokePlace($id): void
    {
        $assignments = $this->placeAssignments;
        foreach ($assignments as $i => $assignment) {
            if ($assignment->isForPlace($id)) {
                unset($assignments[$i]);
                $this->placeAssignments = $assignments;
                return;
            }
        }
        throw new DomainException('Assignment is not found.');
    }

    public function revokePlaces(): void
    {
        $this->placeAssignments = [];
    }

    /**
     * @param string $type
     * @param string $value
     * @throws DomainException
     */
    public function addContact(string $type, string $value)
    {
        $contacts = $this->contacts;
        foreach ($contacts as $i => $contact) {
            if ($contact->isForValue($value)) {
                return;
            }
        }
        $contacts[] = Contact::create($type, $value);
        $this->contacts = $contacts;
    }

    public function revokeContacts()
    {
        $this->contacts = [];
    }

    /**
     * @param string $type
     * @param string $value
     * @throws DomainException
     */
    public function addUrl(string $type, string $value)
    {
        $urls = $this->urls;
        foreach ($urls as $i => $url) {
            if ($url->isForValue($value)) {
                return;
            }
        }
        $urls[] = Url::create($type, $value);
        $this->urls = $urls;
    }

    public function revokeUrls()
    {
        $this->urls = [];
    }

    /**
     * @return string|null
     */
    public function getPoster(): ?string
    {
        return $this->poster;
    }

    /**
     * @return Id
     */
    public function getId(): Id
    {
        return new Id($this->id);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Category
     * @throws DomainException
     */
    public function getCategory(): Category
    {
        return new Category($this->category_id);
    }

    /**
     * @return Date
     * @throws Exception
     */
    public function getDate(): Date
    {
        return new Date(new DateTimeImmutable($this->date_from), new DateTimeImmutable($this->date_to));
    }

    /**
     * @return Contact[]
     */
    public function getContacts(): array
    {
        return $this->contacts;
    }

    /**
     * @return Url[]
     */
    public function getUrls(): array
    {
        return $this->urls;
    }

    /**
     * @return string|null
     */
    public function getCancelReason(): ?string
    {
        return $this->cancel_reason;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return int
     */
    public function getViewCount(): int
    {
        return $this->view_count;
    }

    /**
     * @return int|null
     */
    public function getOwnerId(): ?int
    {
        return $this->owner_id;
    }

    /**
     * @return ActiveQuery
     */
    public function getPlaces(): ActiveQuery
    {
        return $this->hasMany(Place::class, ['id' => 'place_id'])->via('placeAssignments');
    }

    /**
     * @return ActiveQuery
     */
    public function getPlaceAssignments(): ActiveQuery
    {
        return $this->hasMany(PlaceAssignment::class, ['event_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     * @throws InvalidConfigException
     * @throws NotInstantiableException
     */
    public function getOwner(): ActiveQuery
    {
        return $this->hasOne(Yii::$container->get(OwnerInterface::class), ['id' => 'owner_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('event-event', 'ID'),
            'poster' => Yii::t('event-event', 'Poster'),
            'name' => Yii::t('event-event', 'Name'),
            'category_id' => Yii::t('event-event', 'Category ID'),
            'date_from' => Yii::t('event-event', 'Date From'),
            'date_to' => Yii::t('event-event', 'Date To'),
            'status' => Yii::t('event-event', 'Status'),
            'cancel_reason' => Yii::t('event-event', 'Cancel Reason'),
            'description' => Yii::t('event-event', 'Description'),
            'view_count' => Yii::t('event-event', 'View Count'),
            'owner_id' => Yii::t('event-event', 'Owner ID'),
            'created_at' => Yii::t('event-event', 'Created At'),
            'updated_at' => Yii::t('event-event', 'Updated At'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            [
                'class' => SaveRelationsBehavior::class,
                'relations' => ['placeAssignments'],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function transactions(): array
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    /**
     * @throws InvalidArgumentException
     */
    public function afterFind(): void
    {
        $this->urls = array_map(function ($row) {
            return new Url(key($row), $row[key($row)]);
        }, Json::decode($this->getAttribute('urls_json')));
        $this->contacts = array_map(function ($row) {
            return new Contact(key($row), $row[key($row)]);
        }, Json::decode($this->getAttribute('contacts_json')));

        parent::afterFind();
    }

    /**
     * @param bool $insert
     * @return bool
     * @throws InvalidArgumentException
     */
    public function beforeSave($insert): bool
    {
        $this->setAttribute('urls_json', Json::encode(array_filter(array_map(function (Url $url) {
            return array_filter([$url->getType() => $url->getValue()]);
        }, $this->urls))));

        $this->setAttribute('contacts_json', Json::encode(array_filter(array_map(function (Contact $contact) {
            return array_filter([$contact->getType() => $contact->getValue()]);
        }, $this->contacts))));

        if (parent::beforeSave($insert)) {
            return true;
        }
        return false;
    }
}