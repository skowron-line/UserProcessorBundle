<?php

namespace Skowronline\UserProcessorBundle\Tests\Processor;

use Skowronline\UserProcessorBundle\Processor\UserProcessor;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author Krzysztof Skaradziński <skaradzinski.krzysztof@gmail.com>
 */
class UserProcessorTest extends \PHPUnit_Framework_TestCase
{
    public function testProcessorWithoutToken()
    {
        $processor = new UserProcessor($this->mockTokenStorage());
        $this->assertSame([], $processor([]));

        $record = ['data' => '2015-06-01 12:00:00'];
        $this->assertSame($record, $processor($record));
    }

    public function testProcessorWithoutUser()
    {
        $token = $this->mockTokenInterface();
        $storage = $this->mockTokenStorage($token);

        $processor = new UserProcessor($storage);
        $this->assertSame([], $processor([]));

        $record = ['date' => '2015-06-01 12:00:00'];
        $this->assertSame($record, $processor($record));
    }

    public function testUserAsString()
    {
        $token = $this->mockTokenInterface('skowron-line');
        $storage = $this->mockTokenStorage($token);

        $processor = new UserProcessor($storage);
        $this->assertSame(
            [
                'extra' => ['user' => 'skowron-line']
            ],
            $processor([])
        );

        $record = [
            'date' => '2015-06-01 12:00:00',
            'extra' => [
                'branch' => 'master'
            ]
        ];

        $this->assertSame(
            [
                'date' => '2015-06-01 12:00:00',
                'extra' => [
                    'branch' => 'master',
                    'user'   => 'skowron-line'
                ]
            ],
            $processor($record)
        );
    }

    public function testUserAsObject()
    {
        /** @var UserInterface||PHPUnit_Framework_MockObject_MockObject $user */
        $user = $this
            ->getMockBuilder(UserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $user
            ->method('getUserName')
            ->willReturn('skowron-line');

        $token = $this->mockTokenInterface($user);
        $storage = $this->mockTokenStorage($token);

        $processor = new UserProcessor($storage);
        $this->assertSame(
            [
                'extra' => ['user' => 'skowron-line']
            ],
            $processor([])
        );

        $record = [
            'date' => '2015-06-01 12:00:00',
            'extra' => [
                'branch' => 'master'
            ]
        ];

        $this->assertSame(
            [
                'date' => '2015-06-01 12:00:00',
                'extra' => [
                    'branch' => 'master',
                    'user'   => 'skowron-line'
                ]
            ],
            $processor($record)
        );
    }

    /***
     * @param null $returnValue
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|TokenStorageInterface
     */
    private function mockTokenStorage($returnValue = null)
    {
        /** @var TokenStorageInterface|\PHPUnit_Framework_MockObject_MockObject $tokenStorage */
        $tokenStorage = $this
            ->getMockBuilder(TokenStorageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $tokenStorage
            ->method('getToken')
            ->willReturn($returnValue);

        return $tokenStorage;
    }

    /**
     * @param null $returnValue
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|TokenInterface
     */
    private function mockTokenInterface($returnValue = null)
    {
        /** @var TokenInterface|\PHPUnit_Framework_MockObject_MockObject $token */
        $token = $this
            ->getMockBuilder(TokenInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $token
            ->method('getUser')
            ->willReturn($returnValue);

        return $token;
    }
}

