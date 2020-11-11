<?php

namespace tests\Unit;

use App\Databases\Models\ORM\User;
use App\Exceptions\InvalidRequestException;
use App\Databases\ModelFactory;
use App\Services\UserService;
use Exception;
use PHPUnit\Framework\TestCase;

class UsersTest extends TestCase
{
    public function testCreateUser()
    {
        $model = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->setMethods(['save'])
            ->getMock();
        $model
            ->method('save')
            ->willReturn(new User());

       $authManager = $this->getMockBuilder(ModelFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();
        $authManager
            ->method('get')
            ->willReturn($model);

        $service = new UserService($authManager);
        $email = 'test@mail.ru';
        $password = 123456;
        $user = $service->create($email,$password);
        $this->assertEquals('test@mail.ru', $user->email);
    }

    /**
     * @throws \Exception
     */
    public function testLoginUser()
    {
        $user = new User();
        $user->password = password_hash(123456, PASSWORD_BCRYPT);

        $model = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->setMethods(['getUserByCredentials'])
            ->getMock();
        $model
            ->method('getUserByCredentials')
            ->willReturn($user);
        $authManager = $this->getMockBuilder(ModelFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();
        $authManager
            ->method('get')
            ->willReturn($model);

        $service = new UserService($authManager);
        $email = 'test@mail.ru';
        $password = 123456;
        $user = $service->login($email,$password);
        $this->assertEquals('test@mail.ru', $user->email);
    }

    /**
     * @throws \Exception
     */
    public function testLoginWithInvalidPassword()
    {
        $model = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->setMethods(['getUserByCredentials'])
            ->getMock();
        $model
            ->method('getUserByCredentials')
            ->willReturn(new User());

        $authManager = $this->getMockBuilder(ModelFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();
        $authManager
            ->method('get')
            ->willReturn($model);

        $service = new UserService($authManager);
        $email = 'test@mail.ru';
        $password = 123456;

        try {
           $service->login($email,$password);
        } catch (Exception $e) {
            $this->assertInstanceOf(InvalidRequestException::class, $e);
        }
    }
}
