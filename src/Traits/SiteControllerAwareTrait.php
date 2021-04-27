<?php

namespace App\Traits;

use App\Events\ViewRenderParametersEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Trait ControllerAwareTrait
 *
 * @method get(string $id)
 * @method has(string $id)
 */
trait SiteControllerAwareTrait
{
    /**
     * EventDispatcher instance
     *
     * @return EventDispatcher
     */
    public function getEventDispatcher(): EventDispatcher
    {
        if (!$this->has('app.event_dispatcher')) {
            throw new \LogicException('The EventDispatcher is not registered in your application');
        }

        return $this->get('app.event_dispatcher');
    }

    /**
     * Request instance
     *
     * @return Request
     */
    public function getRequest(): Request
    {
        if (!$this->has('request_stack')) {
            throw new \LogicException('The RequestStack is not registered in your application');
        }

        return $this->get('request_stack')->getCurrentRequest();
    }

    /**
     * @inheritDoc
     */
    protected function renderView(string $view, array $parameters = []): string
    {
        /**
         * @var ViewRenderParametersEvent $event
         */
        $event = $this->getEventDispatcher()->dispatch(
            ViewRenderParametersEvent::NAME,
            new ViewRenderParametersEvent($parameters, $this)
        );

        return parent::renderView($view, $event->getParameters());
    }

    /**
     * @inheritDoc
     */
    protected function render(string $view, array $parameters = [], Response $response = null): Response
    {
        /**
         * @var ViewRenderParametersEvent $event
         */
        $event = $this->getEventDispatcher()->dispatch(
            ViewRenderParametersEvent::NAME,
            new ViewRenderParametersEvent($parameters, $this)
        );

        return parent::render($view, $event->getParameters(), $response);
    }
}