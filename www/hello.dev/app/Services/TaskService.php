<?php

namespace App\Services;

use App\Databases\ModelFactory;
use App\Databases\Models\ORM\Task;
use App\Exceptions\ErrorMessages;
use App\Exceptions\InvalidRequestException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TaskService
 *
 * @package App\Services
 */
class TaskService
{
    /** @var UserService */
    protected $model;

    /**
     * TaskService constructor.
     *
     * @param ModelFactory $model
     */
    public function __construct(ModelFactory $model)
    {
        $this->model = $model;
    }

    /**
     * @param string $userId
     *
     * @return Collection
     * @throws \Exception
     */
    public function list(string $userId): Collection
    {
        return $this->getModel()->getAllTasksByUser($userId);
    }

    /**
     * @param int $userId
     * @param string $title
     * @param string $text
     * @param string $finish
     *
     * @return Model
     * @throws InvalidRequestException
     */
    public function create(int $userId, string $title, string $text, string $finish): Model
    {
        $preparedFinish = $this->prepareFinish($finish);

        $this->isCorrectFinishDate($preparedFinish);

        $task = $this->getModel();
        $task->user_id = $userId;
        $task->title = $title;
        $task->text = $text;
        $task->finish = $preparedFinish;
        $task->save();

        return $task;
    }

    /**
     * @param int $userId
     * @param string $title
     * @param string $text
     * @param string $finish
     * @param string $parentId
     *
     * @return Model
     * @throws InvalidRequestException
     * @throws \Exception
     */
    public function createSub(int $userId, string $title, string $text, string $finish, string $parentId): Model
    {
        $preparedFinish = $this->prepareFinish($finish);

        $this->isCorrectFinishDate($preparedFinish);

        $task = $this->getModel();
        $task->user_id = $userId;
        $task->title = $title;
        $task->text = $text;
        $task->finish =  $preparedFinish;
        $task->parent_id = $parentId;
        $task->save();

        return $task;
    }

    /**
     * @param int $taskId
     * @param string $title
     * @param string $text
     * @param string $finish
     *
     * @return bool
     * @throws InvalidRequestException
     */
    public function update(int $taskId, string $title, string $text, string $finish): bool
    {
        $preparedFinish = $this->prepareFinish($finish);

        $this->isCorrectFinishDate($preparedFinish);

        return $this->getModel()->whereId($taskId)->update([
            'title' => $title,
            'text'  => $text,
            'finish' => $finish,
        ]);
    }

    /**
     * @param int $taskId
     *
     * @return bool
     * @throws \Exception
     */
    public function delete(int $taskId): bool
    {
        return $this->getModel()->deleteTaskWithSubTask($taskId);
    }

    /**
     * @param int $taskId
     *
     * @return void
     */
    public function close(int $taskId): void
    {
        $task = Task::findOrFail($taskId);

        if ($task->parent_id == null) {
            $taskIds = Task::whereParentId($taskId)->pluck('id')->toArray();
            array_push($taskIds, $task->id);
        } else {
            $countCompletedSubTask = Task::whereParentId($taskId)->whereStatus(false)->count();
            $taskIds = Task::whereParentId($task->parent_id)->pluck('id')->toArray();
            if ($countCompletedSubTask + 1 == count($taskIds)) {
                array_push($taskIds, $task->parent_id);
            } else {
                $taskIds = array();
                array_push($taskIds, $task->id);
            }
        }

        Task::whereIn('id', $taskIds)->update(['status' => false]);
    }

    /**
     * @return Model
     * @throws \Exception
     */
    public function getModel(): Model
    {
        return $this->model->get(Task::class);
    }

    /**
     * @param $finish
     *
     * @return mixed
     */
    private function prepareFinish($finish)
    {
        return str_replace("/", "-", $finish);
    }

    /**
     * @param $finish
     *
     * @throws InvalidRequestException
     * @throws \Exception
     */
    private function isCorrectFinishDate($finish)
    {
        if (new Carbon($finish) < Carbon::now()) {
            throw new InvalidRequestException(ErrorMessages::END_DATE_CANNOT_BE_LESS_CURRENT_DATE);
        }
    }
}
