<?php

namespace App\Service;

use Twig\Environment;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @author: igor.popravka
 * Date: 12.03.2018
 * Time: 14:57
 */
class Alerts {
    const TYPE_SUCCESS = 'success';
    const TYPE_DANGER = 'danger';
    const TYPE_WARNING = 'warning';

    private $alerts;

    protected $session;

    protected Environment $twig;

    public function __construct(SessionInterface $session, Environment $twig) {
        $this->session = $session;
        $this->alerts = $this->load();
        $this->twig = $twig;
    }

    public function add(string $type, string $message, array $context = []) {
        if (isset($this->alerts[$type])) {
            if (substr($message, -5) == '.twig') {
                $message = $this->twig->render($message, $context);
            }

            $this->alerts[$type][] = $message;
            $this->update();
        }
    }

    public function exist() {
        foreach ($this->alerts as $messages) {
            if (count($messages)) {
                return true;
            }
        }
        return false;
    }

    public function list() {
        foreach ($this->alerts as $type => $messages) {
            foreach ($messages as $message) {
                yield $type => $message;
            }
        }
        $this->clear();
    }

    protected function load() {
        $default = serialize([
            'success' => [],
            'danger' => [],
            'warning' => []
        ]);

        $alerts = unserialize($this->getSession()->get('app-alerts', $default));
        return $alerts;
    }

    protected function update() {
        $alerts = serialize($this->alerts);
        $this->getSession()->set('app-alerts', $alerts);
    }

    protected function clear() {
        $this->getSession()->remove('app-alerts');
    }

    /**
     * @return SessionInterface
     */
    protected function getSession(): SessionInterface {
        return $this->session;
    }
}