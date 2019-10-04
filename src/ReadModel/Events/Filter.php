<?php

namespace Myks92\Vmc\Event\ReadModel\Events;

use DateTimeImmutable;
use Exception;
use Myks92\Vmc\Event\Model\Entity\Events\Category;
use Myks92\Vmc\Event\Model\Entity\Events\Event;
use Myks92\Vmc\Event\Model\Entity\Events\Status;
use Yii;
use yii\base\InvalidArgumentException;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class Filter
 * @author Maxim Vorozhtsov <myks1992@mail.ru>
 */
class Filter extends Model
{
    public const TAB_FUTURE = 'future';
    public const TAB_PAST = 'past';

    public $filter;
    public $tab = self::TAB_FUTURE;
    public $category;
    public $status;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category'], 'integer'],
            [['filter', 'status', 'tab'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function search($params)
    {
        $query = Event::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'category_id' => $this->category,
            'status' => $this->status,
        ]);

        if ($this->tab === self::TAB_FUTURE) {
            $query->orderBy('date_from ASC');
            $query->andFilterWhere(['>=', 'date_to', (new DateTimeImmutable())->format('Y-m-d')]);
        } else {
            $query->andFilterWhere(['<=', 'date_to', (new DateTimeImmutable())->format('Y-m-d')]);
        }

        $query->andFilterWhere(['like', 'name', $this->filter]);

        return $dataProvider;
    }

    /**
     * @return Status[]
     */
    public function getStatuses(): array
    {
        return Status::names();
    }

    /**
     * @return Category[]
     */
    public function getCategories(): array
    {
        return Category::names();
    }

    /**
     * {@inheritdoc}
     */
    public function formName()
    {
        return 'e';
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'filter' => Yii::t('event-event', 'Name'),
            'category' => Yii::t('event-event', 'Category ID'),
            'status' => Yii::t('event-event', 'Status'),
        ];
    }
}
