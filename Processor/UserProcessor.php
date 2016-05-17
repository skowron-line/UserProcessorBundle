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
     * @param array $rekord
     *
     * @return array
     */
    public function __invoke(array $rekord)
    {
        if (null === ($token = $this->storage->getToken())) {
            return $rekord;
        }

        if (null === ($user = $token->getUser())) {
            return $rekord;
        }

        /** @var string|UserInterface $user */
        if (true == $user instanceof UserInterface) {
            $rekord['extra']['user'] = $user->getUsername();
        } elseif (is_string($user)) {
            $rekord['extra']['user'] = $user;
        }

        return $rekord;
    }
}
