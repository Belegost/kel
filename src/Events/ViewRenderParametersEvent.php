<?php

namespace App\Events;

use App\Controller\IFTController;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class ViewRenderParametersEvent
 */
class ViewRenderParametersEvent extends Event
{
    public const NAME = 'view.render.parameters.event';

    /**
     * @var array
     */
    protected array $parameters;

    /**
     * @var IFTController
     */
    protected IFTController $controller;

    /**
     * ViewRenderParametersEvent constructor.
     *
     * @param array $parameters
     * @param IFTController $controller
     */
    public function __construct(array $parameters, IFTController $controller)
    {
        $this->parameters = $parameters;
        $this->controller = $controller;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function addParameter(string $name, $value): self
    {
        if (!isset($this->parameters[$name])) {
            $this->parameters[$name] = $value;
        }

        return $this;
    }

    public function getController(): IFTController
    {
        return $this->controller;
    }
}