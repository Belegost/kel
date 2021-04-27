<?php

namespace App\MessageHandler;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;

/**
 * Class MessageHandlerAbstract
 *
 * @internal
 * @property ContainerInterface $container
 */
abstract class MessageHandlerAbstract implements MessageHandlerInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * Returns true if the service id is defined.
     *
     * @param string $id
     *
     * @return bool
     */
    protected function has(string $id): bool
    {
        return $this->container->has($id);
    }

    /**
     * Gets a container service by its id.
     *
     * @param string $id
     *
     * @return mixed The service
     */
    protected function get(string $id)
    {
        return $this->container->get($id);
    }

    /**
     * Shortcut to return the Doctrine Registry service.
     *
     * @return ManagerRegistry
     *
     * @throws ServiceNotFoundException
     *
     */
    protected function getDoctrine()
    {
        if (!$this->has('doctrine')) {
            throw new ServiceNotFoundException('doctrine');
        }

        return $this->get('doctrine');
    }

    /**
     * Gets a container parameter by its name.
     *
     * @param string $name
     *
     * @return array|bool|float|int|string|null
     *
     * @throws ServiceNotFoundException
     */
    protected function getParameter(string $name)
    {
        if (!$this->container->has('parameter_bag')) {
            throw new ServiceNotFoundException('parameter_bag');
        }

        return $this->container->get('parameter_bag')->get($name);
    }

    /**
     * Stops the retry
     */
    protected function stopRetry()
    {
        throw new UnrecoverableMessageHandlingException();
    }
}