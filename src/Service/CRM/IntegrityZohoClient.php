<?php

namespace App\Service\CRM;

use App\Entity\Zoho\Contact;
use App\Entity\Zoho\Order;
use App\Entity\Zoho\Ticket;
//use  CristianPontes\ZohoCRMClient\Response\MutationResult;
//use Buzz\Browser;
//use Buzz\Client\Curl;
//use CristianPontes\ZohoCRMClient\ZohoCRMClient;
//use CristianPontes\ZohoCRMClient\Transport;
//use CristianPontes\ZohoCRMClient\Transport\TransportRequest;
//use CristianPontes\ZohoCRMClient\Response\Record;
use App\Service\CRM\Request;
use App\Service\ZohoClient;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

/**
 * @author: igor.popravka
 * Date: 09.02.2018
 * Time: 12:51
 */
class IntegrityZohoClient
{
    protected ?string $avatarDir;

    protected ?ZohoClient $zohoClient;

    protected ?TagAwareCacheInterface $cache;

    public function __construct(string $url, array $publicDir, TagAwareCacheInterface $cache)
    {
        $this->avatarDir = $publicDir['avatar'] ?? "";
        $this->cache = $cache;
        $this->zohoClient = new ZohoClient();
        $this->zohoClient->setZohoClientId(getenv('ZOHO_CLIENT_ID'));
        $this->zohoClient->setZohoClientSecret(getenv('ZOHO_CLIENT_SECRET'));
        $this->zohoClient->setAuthRefreshToken(getenv('ZOHO_AUTH_REFRESH_TOKEN'));
        $this->zohoClient->setBaseUri($url);

        $accessToken = $this->cache->get('zoho-access-token', function (ItemInterface $item) {
            $this->zohoClient->generateAccessTokenByRefreshToken();

            if ( ($accessToken = $this->zohoClient->getAccessToken()) !== null ) {
                $item->set($accessToken);
                $item->expiresAfter(50 * 60);
                return $accessToken;
            }
            $item->expiresAfter(0);
            return false;
        });

        $this->zohoClient->setAccessToken($accessToken);
    }

    public function setCache(TagAwareCacheInterface $cache): void
    {
        $this->cache = $cache;
    }

//    private function getXmlTransport() {
//        return new Transport\XmlDataTransportDecorator(
//            new Transport\AuthenticationTokenTransportDecorator(
//                $this->authToken,
//                new IntegrityZohoTransport(
//                    $this->browser,
//                    $this->host . '/crm/private',
//                    IntegrityZohoTransport::FORMAT_XML
//                )
//            )
//        );
//    }

//    private function getJsonTransport() {
//        return new IntegrityDataTransportDecorator (
//            $this->authToken,
//            new IntegrityZohoTransport(
//                $this->browser,
//                $this->host . '/crm/private',
//                IntegrityZohoTransport::FORMAT_JSON
//            )
//        );
//    }

//    /**
//     * @param $module
//     * @return TransportRequest
//     */
//    private function jsonRequest($module) {
//        $request = new Transport\TransportRequest($module);
//        $request->setTransport($this->getJsonTransport());
//        return $request;
//    }

//    /**
//     * @param $module
//     * @return TransportRequest
//     */
//    private function xmlRequest($module) {
//        $request = new Transport\TransportRequest($module);
//        $request->setTransport($this->getXmlTransport());
//        return $request;
//    }

    protected function client($module): ZohoClient
    {
        $this->zohoClient->setModule($module);
        return $this->zohoClient;
    }

    public function getAvatarDir() {
        return $this->avatarDir;
    }

    public function insertTicket(Ticket $ticket)
    {

    }

    /**
     * @param Contact $contact
     * @return bool
     * @throws \ErrorException
     */
    public function insertContact(Contact $contact) {
        $records = $this->client('Contacts')
            ->insertRecords($contact->createRecord(), ['wfTrigger' => 'true'])
            ->getResponseData()['data'];

        if ( count($records) && ($record = array_shift($records)) ) {
            if ( $record['code'] === 'SUCCESS' ) {
                $contact->setId($record['details']['id']);
                $this->uploadContactPhoto($contact);
                return true;
            }
        }
//        if (count($records) && ($record = array_shift($records)) instanceof MutationResult) {
//            /** @var MutationResult $record */
//            if ($record->isError()) {
//                $error = $record->getError();
//                $massage = "[{$error->getCode()}] {$error->getDescription()}";
//                throw new \ErrorException($massage);
//            } else if ($record->isDuplicate()) {
//                throw new \ErrorException("Detected a user with the same registration data. Please contact us if you are registering for the first time.");
//            }
//
//            if ($record->isInserted()) {
//                $contact->setId($record->id);
//                $this->uploadContactPhoto($contact);
//                return true;
//            }
//        }
        return false;
    }

    /**
     * @param Contact $contact
     * @return bool
     */
    public function deleteContact(Contact $contact) {
        try {
            $this->deletePhoto()
                ->id($contact->getId())
                ->request();
        } catch (\Exception $e) {
        }

        $record = $this->client('Contacts')->deleteRecords()
            ->id($contact->getId())
            ->request();

        return $record->isDeleted();
    }

    /**
     * @return Request\UploadPhoto
     */
    public function uploadPhoto() {
        return new Request\UploadPhoto($this->xmlRequest('Contacts'));
    }

    /**
     * @param $id
     * @return string
     */
    public function downloadPhoto($id): ?string {
        $request = new Request\DownloadPhoto($this->jsonRequest('Contacts'));
        $file = $request->id($id)->request();

        if (!empty($file) && strpos($file['content-type'], 'image/') !== false) {
            $fileExt = str_replace('image/', '', $file['content-type']);
            $fileName = "{$id}_Contact_photo.{$fileExt}";
            $fileTemp = "{$this->getAvatarDir()}/{$fileName}";

            if (file_put_contents($fileTemp, $file['content']) !== false) {
                return $fileName;
            }
        }

        return null;
    }

    public function updateContact(Contact $contact) {
        $records = $this->client('Contacts')->updateRecords()
            ->addRecord($contact->createRecord(['Id' => $contact->getId()], false))
            ->triggerWorkflow()
            ->request();

        if (count($records) && ($record = array_shift($records)) instanceof MutationResult) {
            /** @var MutationResult $record */
            if ($record->isUpdated()) {
                $this->uploadContactPhoto($contact);
                return true;
            }
        }
        return false;
    }

    public function updateRecordContact(Contact $contact) {

        $records = $this->client('Contacts')
            ->updateRecords($contact->getId(), $contact->createRecord())
            ->getResponseData()['data'];

        if (count($records) && ($record = array_shift($records))) {
            return $record['code'] === 'SUCCESS';
        }
        return false;
    }

    /**
     * @return Request\DeletePhoto
     */
    public function deletePhoto() {
        return new Request\DeletePhoto($this->xmlRequest('Contacts'));
    }

    public function uploadContactPhoto(Contact $contact) {
        if ($contact->hasAvatar()) {
            $photo = "{$this->getAvatarDir()}/{$contact->getAvatar()}";
            if(is_file($photo)){
                try {
                    $this->uploadPhoto()
                        ->id($contact->getId())
                        ->uploadFromPath($photo)
                        ->request();
                } catch (\Exception $e) {
                }
            }
        } else {
            try {
                $this->deletePhoto()
                    ->id($contact->getId())
                    ->request();
            } catch (\Throwable $e) {
            }
        }
    }

    public function getContact(string $id, $default = null) {
        $result = $this->client('Contacts')
            ->getRecordById($id)
            ->getResponseData()['data'];
        if (count($result) && ($record = array_shift($result))) {
            return $record;
        }
        return $default;
    }

    public function findContactByEmail(string $email) {
        $result = $this->client('Contacts')
            ->getSearchRecordsByPDC()
            ->searchColumn('Email')
            ->searchValue($email)
            ->request();

        if (count($result) && ($record = array_shift($result)) instanceof Record) {
            /** @var Record $record */
            return $record->getData();
        }
        return [];
    }

    public function insertOrder(Order $order) {
        $records = $this->client('PurchaseOrders')->insertRecords()
            ->addRecord($order->createRecord())
            ->request();

        if (count($records) && ($record = array_shift($records)) instanceof MutationResult) {
            /** @var MutationResult $record */
            if ($record->isInserted()) {
                $order->setId($record->id);
                return true;
            }
        }
        return false;
    }
}
