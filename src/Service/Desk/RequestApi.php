<?php

namespace App\Service\Desk;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use App\Form\Data\ContactData;

/**
 * Class RequestApi
 * @package App\Service\Desk
 */
class RequestApi
{
    const CONTACT_URL = 'https://desk.zoho.eu/api/v1/contacts';
    const TICKET_URL = 'https://desk.zoho.eu/api/v1/tickets';
    /**
     * @var string
     */
    private $token;
    /**
     * @var string
     */
    private $organizationId;
    /**
     * @var ContactData
     */
    private $formData;
    /**
     * @var string
     */
    private $contactId;

    /**
     * RequestApi constructor.
     * @param string $token
     * @param string $organizationId
     * @param ContactData $contactData
     */
    public function __construct($token, $organizationId, ContactData $contactData)
    {
        $this->token = $token;
        $this->organizationId = $organizationId;
        $this->formData = $contactData;
    }

    /**
     * @return bool
     */
    public function createTicket()
    {
        $contact = $this->sendRequest(self::CONTACT_URL, $this->contactRequestBody());
        $this->contactId = $contact->id;
        $ticket = $this->sendRequest(self::TICKET_URL, $this->ticketRequestBody());
        if ($ticket) {
            return true;
        }
        return false;
    }

    /**
     * @param $url
     * @param $body
     * @return mixed
     */
    private function sendRequest($url, $body)
    {
        $client = new Client();
        $request = $client->request('POST', $url, [
            RequestOptions::HEADERS => $this->getCredentials(),
            RequestOptions::JSON => $body
        ]);
        $response = json_decode($request->getBody()->getContents());
        return $response;
    }


    /**
     * @return array
     */
    private function getCredentials()
    {
        $headers = array();
        $headers['orgId'] = $this->organizationId;
        $headers['Authorization'] = 'Zoho-authtoken ' . $this->token;
        return $headers;
    }

    /**
     * @return array
     */
    private function contactRequestBody()
    {
        $body = array();
        $body['firstName'] = $this->formData->getUsername();
        $body['email'] = $this->formData->getEmail();
        $body['lastName'] = 'Unknown';
        $body['phone'] = $this->formData->getPhone();
        return $body;
    }

    /**
     * @return array
     */
    private function ticketRequestBody()
    {
        $body = array();
        $body['productId'] = '';
        $body['contactId'] = $this->contactId;
        $body['subject'] = 'Contact form feedback';
        $body['departmentId'] = '11633000000007061';
        $body['email'] = $this->formData->getEmail();
        $body['description'] = $this->formData->getMessage();
        $body['phone'] = $this->formData->getPhone();
        return $body;
    }


}