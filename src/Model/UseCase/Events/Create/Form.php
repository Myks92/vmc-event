<?php


namespace Myks92\Vmc\Event\Model\UseCase\Events\Create;


use DateTimeImmutable;
use elisdn\compositeForm\CompositeForm;
use Exception;
use Myks92\Vmc\Event\Model\Entity\Events\Category;
use Yii;
use yii\web\Request;

/**
 * Class Form
 *
 * @property DateForm $date
 * @property UrlForm[] $urls
 * @property ContactForm[] $contacts
 *
 * @author Maxim Vorozhtsov <myks1992@mail.ru>
 */
class Form extends CompositeForm
{
    public $name;
    public $category;
    public $description = null;
    /**
     * @var Request
     */
    private $request;

    /**
     * Form constructor.
     * @param Request $request
     * @param array $config
     * @throws Exception
     */
    public function __construct(Request $request, $config = [])
    {
        $this->date = new DateForm();

        //Contact
        if (!empty($contacts = $request->post('ContactForm', []))) {
            $this->contacts = array_map(function () {
                return new ContactForm();
            }, $contacts);
        } else {
            $this->contacts = [new ContactForm()];
        }

        //Url
        if (!empty($urls = $request->post('UrlForm', []))) {
            $this->urls = array_map(function () {
                return new UrlForm();
            }, $urls);
        } else {
            $this->urls = [new UrlForm()];
        }

        $this->request = $request;
        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'category'], 'required'],
            [['category'], 'integer'],
            [['category'], 'in', 'range' => array_keys($this->getCategories())],
            [['description'], 'string'],
            [['description'], 'default', 'value' => null],
            [['name'], 'string', 'max' => 255],
            [['name', 'description'], 'trim'],
            [['name'], 'filter', 'filter' => function ($value) {
                $value = preg_replace('/(?:"([^>]*)")(?!>)/', '«$1»', $value);
                return $value;
            }],
        ];
    }

    /**
     * @return Category[]
     */
    public function getCategories(): array
    {
        return Category::names();
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'name' => Yii::t('event-event', 'Name'),
            'category' => Yii::t('event-event', 'Category ID'),
            'description' => Yii::t('event-event', 'Description'),
        ];
    }

    /**
     * @return Command
     */
    public function getCommand(): Command
    {
        $command = new Command();
        $command->name = $this->name;
        $command->category = $this->category;
        $command->dateFrom = $this->date->from;
        $command->dateTo = $this->date->to;
        $command->description = $this->description;

        $command->contacts = array_map(function ($form) {
            return new ContactRow($form['type'], $form['value']);
        }, $this->request->post('ContactForm'));

        $command->urls = array_map(function ($form) {
            return new UrlRow($form['type'], $form['value']);
        }, $this->request->post('UrlForm'));

        return $command;
    }

    /**
     * @return array
     */
    protected function internalForms(): array
    {
        return ['date', 'urls', 'contacts'];
    }
}