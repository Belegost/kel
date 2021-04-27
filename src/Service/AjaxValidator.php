<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Validator\ConstraintViolation;

/**
 * Class AjaxValidator
 * @package App\Service
 */
class AjaxValidator implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public const FIELD_NAME = 'name';
    public const ERROR_STRING = 'error';

    private ?array $result;

    /**
     * @param object $formEntity
     * @return array|null
     */
    public function validate($formEntity): ?array
    {
        return $this->errorBuilder(
            $this->container->get('validator')->validate($formEntity)
        );
    }

    /**
     * @param $errors
     * @return array/null
     */
    private function errorBuilder($errors): ?array
    {
        if (count($errors) > 0) {
            foreach ($errors AS $error) {
                /** @var ConstraintViolation $error */
                $this->result[] = [
                    self::FIELD_NAME => str_replace('data.', '', $error->getPropertyPath()),
                    self::ERROR_STRING => $error->getMessage(),
                ];
            }
            return $this->result;
        }

        return null;
    }
}
