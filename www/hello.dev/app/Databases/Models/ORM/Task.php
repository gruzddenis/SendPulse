<?php

namespace App\Databases\Models\ORM;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Task
 *
 * @package App\Models\ORM
 */
class Task extends Model
{
    /**
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'start',
        'finish',
    ];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tasks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'text',
        'user_id',
        'finish',
        'parent_id',
    ];

    /**
     * @param int     $userId
     *
     * @return Collection
     */
    public function getAllTasksByUser(int $userId): Collection
    {
        return $this->whereUserId($userId)->whereParentId(null)->whereStatus(1)->orderBy('finish')->with('subTasks')->get();
    }

    /**
     * @param int $taskId
     *
     * @return bool
     */
    public function deleteTaskWithSubTask(int $taskId): bool
    {
        return $this->whereId($taskId)->orWhere('parent_id', $taskId)->delete();
    }

    /**
     * @param int $taskId
     * @param $title
     * @param $text
     * @param
     *
     * @return bool
     */
    public function updateTask(int $taskId, string $title, string $text, string $finish): bool
    {
        return $this->whereId($taskId)->update([
            'title' => $title,
            'text'  => $text,
            'finish' => $finish,
        ]);
    }

    /**
     * @return HasMany
     */
    public function subTasks(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }
}
