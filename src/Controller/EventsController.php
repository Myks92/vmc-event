<?php


namespace Myks92\Vmc\Event\Controller;

use DomainException;
use Myks92\Vmc\Event\Model\Entity\Events\Event;
use Myks92\Vmc\Event\Model\Entity\Events\Id;
use Myks92\Vmc\Event\Model\Entity\Events\Status;
use Myks92\Vmc\Event\Model\UseCase\Events\Create;
use Myks92\Vmc\Event\Model\UseCase\Events\Edit;
use Myks92\Vmc\Event\Model\UseCase\Events\Move;
use Myks92\Vmc\Event\Model\UseCase\Events\Poster;
use Myks92\Vmc\Event\Model\UseCase\Events\Remove;
use Myks92\Vmc\Event\Model\UseCase\Events\Status\Activate;
use Myks92\Vmc\Event\Model\UseCase\Events\Status\Cancel;
use Myks92\Vmc\Event\Model\UseCase\Events\Status\Reject;
use Myks92\Vmc\Event\Model\UseCase\Events\Place\Assign;
use Myks92\Vmc\Event\Model\UseCase\Events\Place\Revoke;
use Myks92\Vmc\Event\Model\UseCase\Events\View;
use Myks92\Vmc\Event\ReadModel\Events\Filter;
use Myks92\Vmc\Event\Security\Access\Events\EventChecker;
use Myks92\Vmc\Event\Service\Uploader\FileUploader;
use RuntimeException;
use Throwable;
use Yii;
use yii\base\Exception;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\base\InvalidParamException;
use yii\base\Module;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class EventController
 * @author Maxim Vorozhtsov <myks1992@mail.ru>
 */
class EventsController extends Controller
{
    /**
     * @var EventChecker
     */
    private $checker;

    /**
     * EventController constructor.
     * @param $id
     * @param Module $module
     * @param EventChecker $checker
     * @param array $config
     */
    public function __construct($id, Module $module, EventChecker $checker, $config = [])
    {
        $this->checker = $checker;
        parent::__construct($id, $module, $config);
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['create', 'update', 'move', 'reject', 'cancel', 'delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['create', 'update', 'move', 'reject', 'cancel', 'delete'],
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @return string
     * @throws DomainException
     * @throws InvalidArgumentException
     * @throws InvalidParamException
     */
    public function actionIndex()
    {
        $searchModel = new Filter();
        if (!$this->checker->allowManager()) {
            $searchModel->status = [
                Status::activate()->getValue(),
                Status::rejected()->getValue(),
                Status::cancelled()->getValue()
            ];
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (Yii::$app->request->isAjax) {
            return $this->renderPartial('_list', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'checker' => $this->checker
            ]);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'checker' => $this->checker
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws InvalidArgumentException
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     * @throws RuntimeException
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function actionView($id)
    {
        $event = $this->findModel($id);
        $handler = Yii::createObject(View\Handler::class);

        $handler->handle(new View\Command($event->getId()->getValue()));

        return $this->render('view', [
            'model' => $event,
            'checker' => $this->checker
        ]);
    }

    /**
     * @param $id
     * @return Event
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Event::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('event-event', 'Запрашиваемая страница не найдена.'));
    }

    /**
     * @return string|Response
     * @throws InvalidArgumentException
     * @throws InvalidConfigException
     * @throws RuntimeException
     * @throws Throwable
     */
    public function actionCreate()
    {
        if (!$this->checker->allowCreate()) {
            throw new ForbiddenHttpException('Вам не разрешено производить данное действие!');
        }

        $form = new Create\Form(Yii::$app->request);
        $handler = Yii::createObject(Create\Handler::class);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $command = $form->getCommand();
                $command->owner = Yii::$app->user->getIdentity()->getId();
                $event = $handler->handle($command);
                return $this->redirect(['view', 'id' => $event->getId()->getValue()]);
            } catch (DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('create', [
            'createForm' => $form,
        ]);
    }

    /**
     * @param $id
     * @return string|Response
     * @throws InvalidArgumentException
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     * @throws RuntimeException
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function actionUpdate($id)
    {
        $event = $this->findModel($id);
        $form = new Edit\Form(Yii::$app->request, $event);
        $handler = Yii::createObject(Edit\Handler::class);

        if (!$this->checker->allowEdit($event->getId())) {
            throw new ForbiddenHttpException('Вам не разрешено производить данное действие!');
        }

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $command = $form->getCommand();
                $command->id = $id;
                $handler->handle($command);
                return $this->redirect(['view', 'id' => $event->getId()->getValue()]);
            } catch (DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $event,
            'editForm' => $form,
        ]);
    }

    /**
     * @param $id
     * @return string|Response
     * @throws InvalidArgumentException
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     * @throws RuntimeException
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function actionMove($id)
    {
        $event = $this->findModel($id);
        $form = new Move\Form($event);
        $handler = Yii::createObject(Move\Handler::class);

        if (!$this->checker->allowEdit($event->getId())) {
            throw new ForbiddenHttpException('Вам не разрешено производить данное действие!');
        }

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $handler->handle(new Move\Command($event->getId()->getValue(), $form->from, $form->to));
                Yii::$app->session->setFlash('success', 'Мероприятие перенесено!');
                return $this->redirect(['view', 'id' => $event->getId()->getValue()]);
            } catch (DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('move', [
            'model' => $event,
            'moveForm' => $form,
        ]);
    }

    /**
     * @param $id
     * @return string|Response
     * @throws ForbiddenHttpException
     * @throws InvalidArgumentException
     * @throws InvalidConfigException
     * @throws InvalidParamException
     * @throws NotFoundHttpException
     * @throws RuntimeException
     * @throws StaleObjectException
     * @throws Throwable
     * @throws Exception
     */
    public function actionUploadPoster($id)
    {
        $event = $this->findModel($id);
        $form = new Poster\Upload\Form();
        $handler = Yii::createObject(Poster\Upload\Handler::class);

        if (!$this->checker->allowEdit($event->getId())) {
            throw new ForbiddenHttpException('Вам не разрешено производить данное действие!');
        }

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $uploader = Yii::createObject(FileUploader::class);
            $uploader->remove($event->getPoster());
            $uploaded = $uploader->upload($form->poster);

            $file = new Poster\Upload\File(
                $uploaded->getPath(),
                $uploaded->getName(),
                $uploaded->getSize()
            );

            $handler->handle(new Poster\Upload\Command($event->getId()->getValue(), $file));

            return $this->redirect(['view', 'id' => $event->getId()->getValue()]);
        }

        return $this->render('upload-poster', [
            'model' => $event,
            'posterForm' => $form,
        ]);
    }

    /**
     * @param $id
     * @return Response
     * @throws ForbiddenHttpException
     * @throws InvalidConfigException
     * @throws InvalidParamException
     * @throws NotFoundHttpException
     * @throws RuntimeException
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function actionRemovePoster($id)
    {
        $event = $this->findModel($id);
        $handler = Yii::createObject(Poster\Remove\Handler::class);

        if (!$this->checker->allowEdit($event->getId())) {
            throw new ForbiddenHttpException('Вам не разрешено производить данное действие!');
        }

        $uploader = Yii::createObject(FileUploader::class);
        $uploader->remove($event->getPoster());
        $handler->handle(new Poster\Remove\Command($event->getId()->getValue(), $event->getPoster()));

        return $this->redirect(['view', 'id' => $event->getId()->getValue()]);
    }

    /**
     * @param $id
     * @return Response
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     * @throws RuntimeException
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function actionActivate($id)
    {
        if (!$this->checker->allowChangeStatus()) {
            throw new ForbiddenHttpException('Вам не разрешено производить данное действие!');
        }

        $event = $this->findModel($id);
        $handler = Yii::createObject(Activate\Handler::class);

        try {
            $handler->handle(new Activate\Command($event->getId()->getValue()));
            Yii::$app->session->setFlash('success', 'Мероприятие активировано!');
        } catch (DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['view', 'id' => $event->getId()->getValue()]);
    }

    /**
     * @param $id
     * @return Response
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     * @throws RuntimeException
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function actionReject($id)
    {
        if (!$this->checker->allowChangeStatus()) {
            throw new ForbiddenHttpException('Вам не разрешено производить данное действие!');
        }

        $event = $this->findModel($id);
        $handler = Yii::createObject(Reject\Handler::class);

        try {
            $handler->handle(new Reject\Command($event->getId()->getValue()));
            Yii::$app->session->setFlash('success', 'Мероприятие отклонено!');
        } catch (DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['view', 'id' => $event->getId()->getValue()]);
    }

    /**
     * @param $id
     * @return string|Response
     * @throws InvalidArgumentException
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     * @throws RuntimeException
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function actionCancel($id)
    {
        if (!$this->checker->allowChangeStatus()) {
            throw new ForbiddenHttpException('Вам не разрешено производить данное действие!');
        }

        $event = $this->findModel($id);
        $form = new Cancel\Form();
        $handler = Yii::createObject(Cancel\Handler::class);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $handler->handle(new Cancel\Command($event->getId()->getValue(), $form->reason));
                Yii::$app->session->setFlash('success', 'Мероприятие отменено!');
                return $this->redirect(['view', 'id' => $event->getId()->getValue()]);
            } catch (DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('cancel', [
            'cancelForm' => $form,
            'model' => $event,
        ]);
    }

    /**
     * @param $event_id
     * @return string|Response
     * @throws ForbiddenHttpException
     * @throws InvalidArgumentException
     * @throws InvalidConfigException
     * @throws InvalidParamException
     * @throws NotFoundHttpException
     * @throws RuntimeException
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function actionAssignPlace($event_id)
    {
        if (!$this->checker->allowEdit(new Id($event_id))) {
            throw new ForbiddenHttpException('Вам не разрешено производить данное действие!');
        }

        $event = $this->findModel($event_id);
        $form = new Assign\Form();
        $handler = Yii::createObject(Assign\Handler::class);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $handler->handle(new Assign\Command($event->getId()->getValue(), $form->name, $form->street, $form->city));
                return $this->redirect(['view', 'id' => $event->getId()->getValue()]);
            } catch (DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('assign-place', [
            'assignForm' => $form,
            'model' => $event,
        ]);
    }

    /**
     * @return Response
     * @throws ForbiddenHttpException
     * @throws InvalidConfigException
     * @throws InvalidParamException
     * @throws NotFoundHttpException
     * @throws RuntimeException
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function actionRevokePlace()
    {
        $event_id = Yii::$app->request->post('event_id');
        $id = Yii::$app->request->post('id');
        if (!$this->checker->allowEdit(new Id($event_id))) {
            throw new ForbiddenHttpException('Вам не разрешено производить данное действие!');
        }

        $event = $this->findModel($event_id);
        $handler = Yii::createObject(Revoke\Handler::class);

        try {
            $handler->handle(new Revoke\Command($event->getId()->getValue(), $id));
        } catch (DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['view', 'id' => $event->getId()->getValue()]);
    }

    /**
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws RuntimeException
     * @throws Throwable
     * @throws InvalidConfigException
     * @throws StaleObjectException
     */
    public function actionDelete($id)
    {
        if (!$this->checker->allowRemove()) {
            throw new ForbiddenHttpException('Вам не разрешено производить данное действие!');
        }

        $event = $this->findModel($id);
        $handler = Yii::createObject(Remove\Handler::class);

        try {
            $handler->handle(new Remove\Command($event->getId()->getValue()));
            Yii::$app->session->setFlash('success', 'Мероприятие удалёно!');
        } catch (DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['index']);
    }
}