<?php
/**
 * Created by PhpStorm.
 * User: Grand
 * Date: 28.02.2018
 * Time: 23:26
 */

namespace App\Form\Data;

use Symfony\Component\Validator\Constraints as Assert;

class UploadFileData {
    /**
     * @Assert\Valid
     */
    private $name;

    /**
     * @Assert\Image(
     *     maxSize = "5M",
     *     mimeTypes = {"image/jpeg", "image/gif", "image/png"},
     *     minWidth = 200,
     *     maxWidth = 400,
     *     minHeight = 200,
     *     maxHeight = 400
     * )
     */
    private $data;

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getData() {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data): void {
        $this->data = $data;
    }
}