<?php

namespace App\Controller;

use App\Entity\Integrity\Account;
use App\Entity\MRM\Document;
use App\Service\MRMClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ComplianceController extends IFTController
{
    /**
     * @Route("/revision/create", name="route_revision_create")
     *
     * @param Request $request
     * @param MRMClient $MRMClient
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function createRevision(Request $request, MRMClient $MRMClient) {
        if (!$this->getAuth()->isLogged()) {
            $this->getAuth()->logout();
            return $this->createJSONRError('You are not authorized to this action. Please Sign Up before it.', [], -1001);
        } else if (!$request->isXmlHttpRequest()) {
            return $this->createJSONRBadRequest();
        }

        $fileType = $request->get('type');
        $file = $request->files->all()['file'];
        $email = $this->getAuth()->getAccount()->getEmail();
        //todo: Do some thing with link
        $fileLink = $this->get('upload.file')->init($file,$email,$fileType);

        try {
            $result = $MRMClient->createRevision(
                $this->getAuth()->getAccount()->getId(),
                $fileLink,
                $fileType
            );

            if ($result['status'] == 'success') {
                return $this->createJSONRSuccess();
            } else {
                return $this->createJSONRError($result['message'], $result['data'], $result['code']);
            }
        } catch (\Throwable $t) {
            return $this->createJSONRError('MRM Client error: ' . $t->getMessage());
        }
    }
}