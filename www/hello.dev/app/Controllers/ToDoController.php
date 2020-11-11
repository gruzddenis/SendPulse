<?php

namespace App\Controllers;

use App\Databases\Models\ORM\User;
use App\Exceptions\ErrorMessages;
use App\Exceptions\InvalidRequestException;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class ToDoController
 *
 * @package App\Controllers
 */
class ToDoController extends Controller
{
    /** @var TaskService */
    protected $service;

    /** @var Response */
    protected $response;

    /**
     * ToDoController constructor.
     *
     * @param TaskService $service
     */
    public function __construct(TaskService $service)
    {
        $this->service = $service;
        $this->response = new Response();
    }

    /**
     * @return JsonResponse
     * @throws \Exception
     */
    public function index(): JsonResponse
    {
        $tasks = $this->service->list(User::getAuthUser()->id);

        return $this->response($tasks);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function create(Request $request): JsonResponse
    {
        $this->validate($request);

        if ( $request->get('parent_id') === null) {
            $task = $this->service->create(
                User::getAuthUser()->id,
                $request->get('title'),
                $request->get('text'),
                $request->get('finish')
            );
        } else {
            $task = $this->service->createSub(
                User::getAuthUser()->id,
                $request->get('title'),
                $request->get('text'),
                $request->get('finish'),
                $request->get('parent_id')
            );
        }

        return $this->response($task, 201);
    }

    /**
     * @param Request $request
     * @param string $taskId
     *
     * @return JsonResponse
     * @throws InvalidRequestException
     */
    public function update(Request $request, string $taskId): JsonResponse
    {
        $this->validate($request);

        $updated = $this->service->update(
            $taskId,
            $request->get('title'),
            $request->get('text'),
            $request->get('finish'),
        );

        return $this->response($updated);
    }

    /**
     * @param string $taskId
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function delete(string $taskId): JsonResponse
    {
        $this->service->delete($taskId);

        return $this->response('', 204);
    }

    /**
     * @param string $taskId
     *
     * @return JsonResponse
     */
    public function close(string $taskId): JsonResponse
    {
        $this->service->close($taskId);

        return $this->response('');
    }

    /**
     * @param Request $request
     *
     * @throws InvalidRequestException
     */
    private function validate(Request $request)
    {
        $title = $request->get('title');
        $text = $request->get('text');

        if ($title == "") {
            throw new InvalidRequestException(ErrorMessages::FIELD_TITLE_CAN_NOT_BE_EMPTY);
        }

        if ($text == "") {
            throw new InvalidRequestException(ErrorMessages::FIELD_TEXT_CAN_NOT_BE_EMPTY);
        }
    }

}
