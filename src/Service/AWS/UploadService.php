<?php


namespace App\Service\AWS;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Class UploadService
 * @package App\Service\AWS
 */
class UploadService implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     *
     */
    const BUCKET_LINK = 'https://s3.eu-central-1.amazonaws.com/whatifbitcoin/';

    /**
     * @var string
     */
    private $fileData;
    /**
     * @var string
     */
    private $fileName;
    /**
     * @var string
     */
    private $email;
    /**
     * @var string
     */
    private $type;

    /**
     * @param $file
     * @param $email
     * @param $type
     * @return string
     */
    public function init($file, $email, $type)
    {
        $this->fileData = file_get_contents($file->getRealPath());
        $this->fileName = $file->getClientOriginalName();
        $this->email = $email;
        $this->type = $type;

        return $this->execute();
    }

    /**
     * @return string
     */
    private function execute()
    {
        $file = $this->buildPath();
        $this->getStorage()->upload($file, $this->fileData);
        $fileLink = self::BUCKET_LINK . $file;

//        return $fileLink;
        return $file;
    }

    /**
     * @return string
     */
    private function buildPath()
    {
        $path = 'Found/' . $this->email . '/' . $this->type . '/' . $this->fileName;
        return $path;
    }

    /**
     * @return AmazonS3Service|object
     */
    private function getStorage()
    {
        return $this->container->get('amazon.s3.storage');
    }

}