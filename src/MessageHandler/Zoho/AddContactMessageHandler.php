<?php

namespace App\MessageHandler\Zoho;

use App\Entity\Zoho\Contact;
use App\Entity\Integrity\Account;
use App\Message\Zoho\AddContactMessage;
use App\MessageHandler\MessageHandlerAbstract;

use App\Exception;
use ErrorException;

/**
 * Class AddContactHandler
 */
class AddContactMessageHandler extends MessageHandlerAbstract
{
    use ZohoHandlerAwareTrait;

    public function __invoke(AddContactMessage $message)
    {
        try {
            $em = $this->getDoctrine()->getManager();

            /**
             * @var Account $account
             */
            $account = $em->find(Account::class, $message->getAccountId());
            if (!$account) {
                throw Exception::create('Account with id #' . $message->getAccountId() . ' was not found');
            } else if ($account->getZohoContactId()) {
                $this->stopRetry();
            }

            $contact = new Contact(
                [
                    'First_Name' => $account->getFirstName() ?? '',
                    'Last_Name' => $account->getLastName() ?? $account->getUsername(),
                    'Email' => $account->getEmail(),
                    'Phone' => $account->getPhoneNumber(),
                    'Integrity_Fund_Status' => Contact::STATUS_REGISTERED,
                    'Username_Integrity_Fund' => $account->getUsername(),
                    'Integrity_Fund_Profile_Link' => $this->getRouter()->generate(
                        'route_signin_viewmode',
                        ['public_key' => $account->getPublicKey()]
                    ),
                ]
            );

            if (!$this->getZohoClient()->insertContact($contact)) {
                throw Exception::create('Failed to create new Zoho contact');
            }

            $account->setZohoContactId($contact->getId());
            $em->persist($account);
            $em->flush();

        } catch (Exception $e) {
            echo $e->getMessage();
            dd($e);
        } catch (ErrorException $e) {
            echo $e->getMessage();
            dd($e);
        }
    }
}
