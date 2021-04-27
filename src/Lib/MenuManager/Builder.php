<?php

namespace App\Lib\MenuManager;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Class MenuManager
 */
class Builder
{
    use ContainerAwareTrait;

    protected Menu $menu;

    /**
     * Returns new instance of Menu
     *
     * @param string $name
     *
     * @return Menu
     */
    public function menu(string $name): Menu
    {
        return ($this->menu = new Menu($name, $this));
    }

    public function setOptions(array $options): Menu
    {
        $this->menu->setOptions($options);

        return $this->menu;
    }

    /**
     * Return name of the current rote
     *
     * @return string
     */
    public function getCurrentRoute(): string
    {
        return $this->container->get('request_stack')->getCurrentRequest()->get('_route');
    }

    /**
     * Return generated link with route
     *
     * @param string $route
     * @param array $parameters
     *
     * @return string
     */
    public function generateLink(string $route, array $parameters = []): string
    {
        return $this->container->get('router')->generate($route, $parameters);
    }
}