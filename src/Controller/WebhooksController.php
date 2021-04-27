<?php


namespace App\Controller;


use App\Entity\Integrity\AccountState;
use App\Entity\Integrity\SumSubReview;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class WebhooksController extends IFTController
{

    /**
     * @Route("/webhooks/sum-sub/reviewed", name="route_webhooks_sumsub_reviewed", methods={"POST"})
     *
     * @param Request $request
     */
    public function routeSumSubReviewed(Request $request)
    {
        try {
            $data = $request->toArray();
            $account = $this->getDbClient()->getRepository('Integrity:Account')
                ->find($data['externalUserId']);

            if ( ! $account ) {
                $this->getLogger()->error('Unknown applicant id: ' . $data['externalUserId']);
                return $this->json([
                    'success' => false,
                    'message' => 'Unknown applicant id: ' . $data['externalUserId'],
                ]);
            }

            $answer = $data['reviewResult']['reviewAnswer'];
            $sumSubReview = new SumSubReview();
            $sumSubReview
                ->setAccount($account)
                ->setApplicantId($data['applicantId'])
                ->setInspectionId($data['inspectionId'])
                ->setCorellationId($data['correlationId'])
                ->setReviewResult($answer)
                ->setStatus($data['reviewStatus'])
                ->setCreatedAt(Carbon::createFromTimeString($data['createdAt'])->toDateTime());
            if ( $answer === 'RED' ) {
                $sumSubReview
                    ->setModerationComment($data['reviewResult']['moderationComment'])
                    ->setClientComment($data['reviewResult']['clientComment'])
                    ->setRejectLabels($data['reviewResult']['rejectLabels']);
            } else if ( $answer === 'GREEN') {
                $state = $this->getDbClient()->getRepository('Integrity:AccountState')
                    ->find(AccountState::APPROVED);
                $account->setState($state);
                $this->getDbClient()->getEntityManager()->persist($account);
            }

            $this->getDbClient()->getEntityManager()->persist($sumSubReview);
            $this->getDbClient()->getEntityManager()->flush();
        } catch (\Throwable $e) {
            $this->getLogger()->error('[SumSub WebHook] Error: ' . $e->getMessage());
        }


        return $this->json([
            'success' => true,
        ]);
    }
}
