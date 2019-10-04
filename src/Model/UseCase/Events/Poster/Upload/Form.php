<?php


namespace Myks92\Vmc\Event\Model\UseCase\Events\Poster\Upload;


use yii\base\Model;
use yii\web\UploadedFile;

/**
 * Class Form
 * @author Maxim Vorozhtsov <myks1992@mail.ru>
 */
class Form extends Model
{
    public $poster;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            //[['poster'], 'required', 'message' => 'Необходимо выбрать афишу!'],
            [['poster'], 'file', 'extensions' => ['png', 'jpg'], 'maxSize' => 1024 * 1024 * 3],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'poster' => false,
        ];
    }

    /**
     * @return bool
     */
    public function beforeValidate(): bool
    {
        if (parent::beforeValidate()) {
            $this->poster = UploadedFile::getInstance($this, 'poster');
            return true;
        }
        return false;
    }
}