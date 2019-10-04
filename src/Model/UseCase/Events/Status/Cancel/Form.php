<?php


namespace Myks92\Vmc\Event\Model\UseCase\Events\Status\Cancel;


use Yii;
use yii\base\Model;

/**
 * Class Form
 * @author Maxim Vorozhtsov <myks1992@mail.ru>
 */
class Form extends Model
{
    public $reason;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['reason'], 'required'],
            [['reason'], 'string', 'max' => 255],
            [['reason'], 'trim'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'reason' => Yii::t('event-event', 'Cancel Reason'),
        ];
    }
}