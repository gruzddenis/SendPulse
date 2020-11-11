<?php

namespace tests\Unit;

use App\Databases\Models\ORM\Task;
use App\Databases\ModelFactory;
use App\Exceptions\InvalidRequestException;
use App\Services\TaskService;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\TestCase;

class TasksTest extends TestCase
{
    public function testList()
    {
        $collection = (new Collection([
            'title' => 'test',
        ]));
        $model = $this->getMockBuilder(Task::class)
            ->disableOriginalConstructor()
            ->setMethods(['getAllTasksByUser'])
            ->getMock();
        $model
            ->method('getAllTasksByUser')
            ->willReturn($collection);

        $authManager = $this->getMockBuilder(ModelFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();
        $authManager
            ->method('get')
            ->willReturn($model);

        $service = new TaskService($authManager);

        $tasks = $service->list(1);

        $this->assertEquals('test', $tasks->first());
    }

    public function testCreate()
    {
        $task = new Task();
        $userId = 1;
        $title = 'test';
        $text = 'test text';
        $finish = '2020-12-22 10:49:00';

        $model = $this->getMockBuilder(Model::class)
            ->disableOriginalConstructor()
            ->setMethods(['save'])
            ->getMock();
        $model
            ->method('save')
            ->willReturn($task);

        $modelFactory = $this->getMockBuilder(ModelFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();
        $modelFactory
            ->method('get')
            ->willReturn($model);

        $service = new TaskService($modelFactory);

        $task = $service->create($userId, $title, $text, $finish);

        $this->assertEquals("test text", $task->text);
    }

    /**
     * @expectedException InvalidRequestException
     */
    public function testCreateEndDateIsLessThanCurrentDate()
    {
        $task = new Task();
        $userId = 1;
        $title = 'test';
        $text = 'test text';
        $finish = '2019-05-22 10:49:00';

        $model = $this->getMockBuilder(Model::class)
            ->disableOriginalConstructor()
            ->setMethods(['save'])
            ->getMock();
        $model
            ->method('save')
            ->willReturn($task);

        $modelFactory = $this->getMockBuilder(ModelFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();
        $modelFactory
            ->method('get')
            ->willReturn($model);
        try {
            $service = new TaskService($modelFactory);
            $service->create($userId, $title, $text, $finish);
        } catch (Exception $e) {
            $this->assertInstanceOf(InvalidRequestException::class, $e);
        }
    }

    public function testCreateSub()
    {
        $task = new Task();

        $userId = 1;
        $title = 'test';
        $text = 'test text';
        $finish = '2020-12-22 10:49:00';
        $parentId = '2';

        $model = $this->getMockBuilder(Model::class)
            ->disableOriginalConstructor()
            ->setMethods(['save'])
            ->getMock();
        $model
            ->method('save')
            ->willReturn($task);

        $modelFactory = $this->getMockBuilder(ModelFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();
        $modelFactory
            ->method('get')
            ->willReturn($model);

        $service = new TaskService($modelFactory);

        $task = $service->createSub($userId, $title, $text, $finish, $parentId);

        $this->assertEquals("test text", $task->text);
    }
}
