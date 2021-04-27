<?php

namespace App\MessageHandler\Zoho;

use App\Entity\Integrity\Account;
use App\Entity\Zoho\Contact;
use App\Exception;
use App\Message\Zoho\UpdateContactMessage;
use App\MessageHandler\MessageHandlerAbstract;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class UpdateContactMessageHandler extends MessageHandlerAbstract
{
    use ZohoHandlerAwareTrait;

    public function __invoke(UpdateContactMessage $message)
    {
        try {
            /**
             * @var Account $account
             */
            $account = $this->getDoctrine()->getManager()->find(Account::class, $message->getAccountId());
            if (!$account) {
                throw Exception::create('Account with id #' . $message->getAccountId() . ' was not found');
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

            if (!$this->getZohoClient()->updateRecordContact($contact)) {
                throw Exception::create('Failed to create new Zoho contact');
            }

        } catch (Exception $e) {
            echo $e->getMessage();
            dd($e);
        } catch (ErrorException $e) {
            echo $e->getMessage();
            dd($e);
        }
    }
}
