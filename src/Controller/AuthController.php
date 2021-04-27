<?php

namespace App\Controller;

use App\Entity\Integrity\Account;
use App\Form\ChangePasswordAPIType;
use App\Form\Data\ChangePasswordAPIData;
use App\Form\Data\ResetPasswordData;
use App\Form\Data\SignUpData;
use App\Form\Google2FAType;
use App\Form\ResetPasswordType;
use App\Form\SignUpType;
use App\Form\SignInType;
use App\Model\Google2FASettings;
use App\Model\PersonalDataSettings;
use App\Service\Alerts;
use App\Service\Auth;
use App\Service\TokenManager;
use App\Service\Messenger\Zoho as ZohoMessengerService;
use App\Service\Messenger\Internal as InternalMessengerService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\AjaxValidator;

/**
 * Class AuthController
 * @package App\Controller
 */
class AuthController extends IFTController {
    /**
     * @Route("/signup", name="route_signup")
     *
     * @param Request $request
     * @param AjaxValidator $validator
     * @param ZohoMessengerService $zoho
     * @param InternalMessengerService $internal
     *
     * @return JsonResponse|Response
     * @throws \Exception
     */
    public function signUp(Request $request, AjaxValidator $validator, ZohoMessengerService $zoho, InternalMessengerService $internal): Response
    {
        $formDataClass = new SignUpData();
        $formSignUp = $this->createForm(SignUpType::class, $formDataClass);

        if ($request->isXmlHttpRequest()) {
            $formSignUp->handleRequest($request);

            $errors = $validator->validate($formDataClass);
            $signUpData = $formSignUp->getData();

            if (!is_null($errors)) {
                return $this->json($errors);
            }

            if ($formSignUp->isSubmitted() && $formSignUp->isValid()) {
                $this->handleAvatarFile($signUpData);

                try {
                    $account = $this->getAuth()->createAccount($signUpData);
                    $zoho->addContact($account);
                    $internal->confirmEmailAddress($account);
                    $internal->sendWelcomeEmail($signUpData);

                    $this->addAlert(Alerts::TYPE_SUCCESS, 'alerts/confirm-email.alert.html.twig', [
                        'INTEGRITY_EMAIL' => $this->getContacts('email'),
                    ]);

                    return $this->json(true);
                } catch (\Exception $e) {
                    $this->getLogger()->critical($e->getMessage());
                    throw new \Exception($e->getMessage());
                }
            }
        }

        return $this->render('controller/auth/signup.html.twig', $this->makeRenderData('signup', [
            'form_signup' => $formSignUp->createView(),
            'country_code' => $this->detectCountryCode($request),
        ]));
    }

    /**
     * @Route("/signin", name="route_signin")
     *
     * @param Request $request
     * @param Auth $auth
     * @param InternalMessengerService $internal
     * @param ZohoMessengerService $zoho
     *
     * @return JsonResponse
     */
    public function signIn(Request $request, Auth $auth, InternalMessengerService $internal, ZohoMessengerService $zoho) {
        if ($request->isXmlHttpRequest()) {
            $formSignIn = $this->createForm(SignInType::class);
            $formSignIn->handleRequest($request);

            if ($formSignIn->isSubmitted() && $formSignIn->isValid() && $auth->login($formSignIn->getData())) {
                /**
                 * @var PersonalDataSettings $userSettings
                 */
                $userSettings = $this->get('app.service.model')->factory(
                    PersonalDataSettings::class,
                    ['accountId' => $auth->getAccount()->getId()]
                );

                if ( ($zohoId = $auth->getAccount()->getZohoContactId()) === null ) {
                    $zoho->addContact($auth->getAccount());
                }

                if (!$userSettings->isEmailConfirmed()) {
                    $internal->confirmEmailAddress($auth->getAccount());

                    $this->getAuth()->logout();

                    $this->addAlert(
                        Alerts::TYPE_SUCCESS,
                        'alerts/confirm-email.alert.html.twig',
                        [
                            'INTEGRITY_EMAIL' => $this->getContacts('email'),
                            'BEFORE_ACTION' => 'before sign-in'
                        ]
                    );

                    return $this->createJSONRSuccess(
                        'Success',
                        [
                            'redirectToDashboard' => false,
                        ]
                    );
                }

                return $this->createJSONRSuccess(
                    'Success',
                    [
                        'redirectToDashboard' => !$auth->isGoogle2FAStarted(),
                    ]
                );
            }

            return $this->createJSONRError('Incorrect username or password');
        }

        return $this->createJSONRBadRequest();
    }

    /**
     * @Route("/signin/view-mode/{public_key}", name="route_signin_viewmode")
     *
     * @param Request $request
     * @param string $public_key
     *
     * @return RedirectResponse
     */
    public function signInViewMode(Request $request, string $public_key) {
        if ($request->isMethod('GET') && $this->getAuth()->loginByPublicKey($public_key)) {
            return $this->redirectToRoute('route_user_dashboard');
        }

        $this->addAlert(Alerts::TYPE_DANGER, 'Failed during authorization. Please contact with us.');

        return $this->redirectToRoute('route_home');
    }

    /**
     * @Route("/signout", name="route_signout")
     *
     * @return RedirectResponse
     */
    public function signOut() {
        $this->getAuth()->logout();

        return $this->redirectToRoute('route_home');
    }

    /**
     * @Route("/password/reset", name="route_password_reset")
     *
     * @param Request $request
     * @param InternalMessengerService $internal
     *
     * @return Response
     */
    public function resetPassword(Request $request, InternalMessengerService $internal) {
        if ($request->isXmlHttpRequest()) {
            $formResetPassword = $this->createForm(ResetPasswordType::class);
            $formResetPassword->handleRequest($request);

            if ($formResetPassword->isSubmitted() && $formResetPassword->isValid()) {
                /** @var ResetPasswordData $resetPasswordData */
                $resetPasswordData = $formResetPassword->getData();

                /** @var Account $account */
                if (($account = $this->getAuth()->isAccountExist($resetPasswordData->getForUsername())) instanceof Account) {
                    try {
                        $internal->sendPasswordResetEmail($account);

                        $this->addAlert(Alerts::TYPE_SUCCESS, 'alerts/password-reset-email.alert.html.twig');

                        return $this->createJSONRSuccess();
                    } catch (\Throwable $e) {
                        $this->getLogger()->critical($e->getMessage(), $e->getTrace());
                        return $this->createJSONRError("Mail transport error. Please try again later.");
                    }
                }

                return $this->createJSONRError("Wrong username. Please check value and try again.");
            }
        }

        return $this->createJSONRBadRequest();
    }

    /**
     * @Route("/password/change/{token}", name="route_password_change")
     *
     * @param Request $request
     * @param TokenManager $tokenManager
     * @param string $token
     * @param InternalMessengerService $internal
     *
     * @return Response
     */
    public function changePassword(
        Request $request,
        TokenManager $tokenManager,
        $token,
        InternalMessengerService $internal
    ) {
        $tokenManager->loadToken($token);

        if ($tokenManager->isTokenValid()) {
            $formPasswordChange = $this->createForm(ChangePasswordAPIType::class);
            $formPasswordChange->handleRequest($request);
            $publicKey = $tokenManager->getTokenData()['PUBLIC_KEY'] ?? null;

            $account = $this->getAuth()->isAccountExist($publicKey);

            if ($account) {
                /**
                 * @var PersonalDataSettings $userSettings
                 */
                $userSettings = $this->get('app.service.model')->factory(
                    PersonalDataSettings::class,
                    ['accountId' => $account->getId()]
                );

                if (!$userSettings->isEmailConfirmed()) {
                    $internal->confirmEmailAddress($account);

                    $this->getAuth()->logout();

                    $this->addAlert(
                        Alerts::TYPE_SUCCESS,
                        'alerts/confirm-email.alert.html.twig',
                        [
                            'INTEGRITY_EMAIL' => $this->getContacts('email'),
                            'BEFORE_ACTION' => 'before change password'
                        ]
                    );

                    return $this->redirectToRoute('route_home');
                }

                if ($formPasswordChange->isSubmitted() && $formPasswordChange->isValid()) {
                    /** @var ChangePasswordAPIData $changeData */
                    $changeData = $formPasswordChange->getData();

                    if ($this->getAuth()->changePasswordAPI($publicKey, $changeData->getPassword())) {
                        $this->getAuth()->logout();
                        $tokenManager->resetToken();
                        $this->addAlert(Alerts::TYPE_SUCCESS, 'alerts/password-reset-success.alert.html.twig');
                    } else {
                        $this->addAlert(
                            Alerts::TYPE_DANGER,
                            'alerts/password-reset-failed.alert.html.twig',
                            [
                                'INTEGRITY_EMAIL' => $this->getContacts('email'),
                                'MESSAGE' => 'Wrong credentials'
                            ]
                        );
                    }

                    return $this->redirectToRoute('route_home');
                }
            } else {
                $this->addAlert(
                    Alerts::TYPE_DANGER,
                    'alerts/password-reset-failed.alert.html.twig',
                    [
                        'INTEGRITY_EMAIL' => $this->getContacts('email'),
                        'MESSAGE' => 'Wrong credentials'
                    ]
                );
            }

            return $this->render(
                'controller/auth/password-change.html.twig',
                $this->makeRenderData(
                    'route_password_change',
                    [
                        'form_password_change' => $formPasswordChange->createView(),
                        'form_password_change_errors' => $this->formErrors2Array($formPasswordChange),
                    ]
                )
            );
        }

        $this->addAlert(Alerts::TYPE_DANGER, "Link for reset password doesn't a valid.");

        return $this->redirectToRoute('route_home');
    }

    /**
     * @Route("/switch/usd-rates/{val}", name="route_switch_usd_rates")
     *
     * @param Request $request
     * @param string $val
     *
     * @return JsonResponse
     */
    public function usdRates(Request $request, string $val) {
        if (!$this->getAuth()->isLogged()) {
            $this->getAuth()->logout();

            return $this->createJSONRError('You are not authorized to this action. Please Sign Up before it.');
        } elseif (!$request->isXmlHttpRequest()) {

            return $this->createJSONRBadRequest();
        }

        $val = ($val == 'on');
        $this->getAuth()->switchUSDRates($val);

        return $this->createJSONRSuccess();
    }

    /**
     * @Route("/google2FA", name="app.auth.google2FA")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function google2FA(Request $request)
    {
        $google2FAForm = $this->createForm(Google2FAType::class);
        $google2FAForm->handleRequest($request);

        if ($google2FAForm->isSubmitted()) {
            if ($google2FAForm->isValid()) {
                /** @var ChangePasswordAPIData $changeData */
                $verificationCode = $google2FAForm->get('verificationCode')->getData();
                /**
                 * @var Google2FASettings $userSettings
                 */
                $userSettings = $this->get('app.service.model')->factory(
                    Google2FASettings::class,
                    ['accountId' => $this->getAuth()->getAccount()->getId()]
                );

                if ($userSettings->checkGoogle2FARecoveryCode($verificationCode)) {
                    $userSettings->refreshGoogle2FARecoveryCodes()
                        ->setGoogle2FAShowQrUrl(true);

                    return $this->createJSONRSuccess('Success', ['google2FARecovery' => true]);
                } else if ($userSettings->checkGoogle2FAVerificationCode($verificationCode)) {
                    $this->getAuth()->finishGoogle2FA();

                    if ($userSettings->isGoogle2FAShowQrUrl()) {
                        $userSettings->setGoogle2FAShowQrUrl(false);
                    }

                    return $this->createJSONRSuccess();
                }
            }

            return $this->createJSONRError('Wrong verification code');
        }

        return $this->createJSONRBadRequest();
    }

    /**
     * @Route("/confirmEmailAddress/{token}", name="app.auth.confirmEmailAddress")
     * @param TokenManager $tokenManager
     * @param string $token
     *
     * @return JsonResponse|RedirectResponse
     */
    public function confirmEmailAddress(TokenManager $tokenManager, string $token)
    {
        $tokenManager->loadToken($token);

        if($tokenManager->isTokenValid()) {
            $userId = $tokenManager->getTokenData()['accountId'];
            /**
             * @var Account $account
             */
            $account = $this->getDoctrine()->getManager()->find(Account::class, $userId);

            if($account) {
                /**
                 * @var PersonalDataSettings $userSettings
                 */
                $userSettings = $this->get('app.service.model')->factory(
                    PersonalDataSettings::class,
                    ['accountId' => $account->getId()]
                );

                $userSettings->setEmailConfirmed(true);

                $tokenManager->resetToken();

                $this->addAlert(
                    Alerts::TYPE_SUCCESS,
                    'alerts/account-activated.alert.html.twig',
                    [
                        'CLIENT_NAME' => $account->getUsername(),
                    ]
                );

                return $this->redirectToRoute('route_home');
            }
        }

        $this->addAlert(
            Alerts::TYPE_DANGER,
            'alerts/account-activated-failed.alert.html.twig',
        );

        return $this->redirectToRoute('route_home');
    }


    private function detectCountryCode(Request $request): string
    {
	    $ip = $request->getClientIp();
//	    $ip = '93.73.15.4';
	    return file_get_contents('https://ipapi.co/' . $ip . '/country_code/');
    }
}
