<?php

/*
 * This file is part of the Monolog package.
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Troopers\MetricsBundle\Monolog\Processor;

use Monolog\Logger;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Serializer\Serializer;
use Troopers\MetricsBundle\Monolog\SerializeContextItem;

/**
 * Injects base User data in all records.
 * if user is serializable, set the metrics.serializer_user_groups in th configuration to automate advance processing.
 *
 * @author Leny Bernard
 */
class UserProcessor
{
    private $level;
    private static $cache;
    /**
     * @var TokenStorage
     */
    private $tokenStorage;
    /**
     * @var Serializer
     */
    private $serializer;
    private $serializerUserGroups;

    /**
     * UserProcessor constructor.
     *
     * @param TokenStorage $tokenStorage
     * @param Serializer   $serializer
     * @param array        $serializerUserGroups
     * @param int          $level
     */
    public function __construct(TokenStorage $tokenStorage,
                                $serializer,
                                array $serializerUserGroups,
                                $level = Logger::DEBUG)
    {
        $this->level = Logger::toMonologLevel($level);
        $this->tokenStorage = $tokenStorage;
        $this->serializer = $serializer;
        $this->serializerUserGroups = $serializerUserGroups;
    }

    /**
     * @param array $record
     *
     * @return array
     */
    public function __invoke(array $record)
    {
        // return if the level is not high enough
        if ($record['level'] < $this->level) {
            return $record;
        }

        $record['context'] = array_merge($record['context'], $this->getUserInfo($this->tokenStorage->getToken()));

        return $record;
    }

    /**
     * @param TokenInterface $token
     *
     * @return array
     */
    private function getUserInfo(TokenInterface $token = null)
    {
        if (self::$cache) {
            return self::$cache;
        }

        $infos = [
            'authenticated' => false,
        ];
        if (
            null !== $token &&
            $token->isAuthenticated() &&
            is_object($token->getUser())
        ) {
            $infos = [
                'authenticated' => true,
                'id'            => $token->getUser()->getId(),
                'username'      => $token->getUsername(),
            ];

            //if a serializer group is given, serialize and add to the extra fields
            if (count($this->serializerUserGroups)) {
                $serializeContextItem = new SerializeContextItem(
                    $token->getUser(),
                    $this->serializerUserGroups
                );
                $userInfos = $serializeContextItem->serialize($this->serializer);

                foreach ($userInfos as $property => $value) {
                    $userInfos['user_'.$property] = $value;
                    unset($userInfos[$property]);
                }

                $infos = array_merge(
                    $userInfos,
                    $infos
                );
            }
        }

        return self::$cache = $infos;
    }
}
