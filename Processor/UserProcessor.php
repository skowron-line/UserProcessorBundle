<?php

namespace Skowronline\UserProcessorBundle\Processor;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author Krzysztof Skaradziński <skaradzinski.krzysztof@gmail.com>
 */
class UserProcessor
{
    /**
     * @var TokenStorageInterface
     */
    private $storage;

    /**
     * @param TokenStorageInterface $storage
     */
    public function __construct(TokenStorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param array $record
     *
     * @return array
     */
    public function __invoke(array $record)
    {
        if (null === ($token = $this->storage->getToken())) {
            return $record;
        }

        if (null === ($user = $token->getUser())) {
            return $record;
        }

        /** @var string|UserInterface $user */
        if (true === $user instanceof UserInterface) {
            $record['extra']['user'] = $user->getUsername();
        } elseif (true === is_string($user)) {
            $record['extra']['user'] = $user;
        }

        return $record;
    }
}
