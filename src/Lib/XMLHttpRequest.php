<?php
/**
 * Created by PhpStorm.
 * User: Grand
 * Date: 08.05.2018
 * Time: 23:06
 */

namespace App\Lib;

use Symfony\Component\HttpFoundation\Request;

abstract class XMLHttpRequest {
    /** @var array */
    private $errors = [];

    /** @var Request */
    private $request;

    abstract protected function validate();

    public function handleRequest(Request $request) {
        $this->request = $request;

        $refAjaxType = new \ReflectionObject($this);

        foreach ($refAjaxType->getProperties() as $refProperty) {
            /** @var \ReflectionProperty $refProperty */
            $method_name = $this->generateMethodName('set', $refProperty->name);

            if ($request->get($refProperty->name) && $refAjaxType->hasMethod($method_name)) {
                /** @var \ReflectionMethod $refMethod */
                $refMethod = $refAjaxType->getMethod($method_name);
                $refMethod->invoke($this, $request->get($refProperty->name));
            }
        }
    }

    private function generateMethodName(string $prefix, string $property_name): string {
        $name_units = explode('_', $property_name);
        $name_units = array_map(function ($v) {
            return ucfirst($v);
        }, $name_units);

        return $prefix . implode('', $name_units);
    }

    public function isValid(): bool {
        $this->validate();
        return empty($this->errors);
    }

    public function addError(string $massage) {
        if (!in_array($massage, $this->errors)) {
            $this->errors[] = $massage;
        }
        return $this;
    }

    public function getErrors(): array {
        return $this->errors;
    }

    public function isPost(): bool {
        return isset($this->request) && $this->request->isXmlHttpRequest() && $this->request->isMethod('POST');
    }
}