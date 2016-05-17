<?php

namespace Skowronline\UserProcessorBundle\Tests\Processor;

use Skowronline\UserProcessorBundle\Processor\UserProcessor;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @author Krzysztof Skaradziński <skaradzinski.krzysztof@gmail.com>
 */
class UserProcessorTest extends \PHPUnit_Framework_TestCase
{
    public function testProcessorWithoutToken()
    {
        /** @var TokenStorageInterface|\PHPUnit_Framework_MockObject_MockObject $tokenStorage */
        $tokenStorage = $this
            ->getMockBuilder(TokenStorageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $tokenStorage
            ->expects($this->once())
            ->method('getToken')
            ->willReturn(null);

        $record = [];

        $processor = new UserProcessor($tokenStorage);
        $this->assertSame([], $processor($record));
    }
}

