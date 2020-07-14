<?php

namespace App\Controller;

use App\Controller\AbstractAppController;
use App\Exception\CallException;
use App\Form\CreateAppUserType;
use App\Manager\CallManager;
use App\Model\CallRequestModel;
use App\Model\AppUserModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class SecurityController extends AbstractAppController
{
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/", name="app_login")
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('cluster');
        }

        $callRequest = new CallRequestModel();
        $callRequest->setMethod('HEAD');
        $callRequest->setPath('/.elastictsearch-admin-users');
        $callResponse = $this->callManager->call($callRequest);

        if (Response::HTTP_NOT_FOUND == $callResponse->getCode()) {
            return $this->redirectToRoute('register');
        }

        if ($error = $authenticationUtils->getLastAuthenticationError()) {
            $this->addFlash('danger', $error->getMessageKey());
        }

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->renderAbstract($request, 'Modules/security/login.html.twig', [
            'last_username' => $lastUsername,
        ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): RedirectResponse
    {
        return new RedirectResponse($this->generateUrl('app_login'));
    }

    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request): Response
    {
        $callRequest = new CallRequestModel();
        $callRequest->setMethod('HEAD');
        $callRequest->setPath('/.elastictsearch-admin-users');
        $callResponse = $this->callManager->call($callRequest);

        if (Response::HTTP_OK == $callResponse->getCode()) {
            throw new AccessDeniedHttpException();
        }

        $user = new AppUserModel();
        $form = $this->createForm(CreateAppUserType::class, $user, ['context' => 'register']);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $json = [
                    'settings' => [
                        'index' => [
                            'number_of_shards' => 1,
                            'auto_expand_replicas' => '0-1',
                        ],
                    ]
                ];
                if (true == $this->callManager->checkVersion('7.0')) {
                    $json['mappings'] = [
                        'properties' => [
                            'email' => [
                                'type' => 'keyword',
                            ],
                            'password' => [
                                'type' => 'keyword',
                            ],
                            'roles' => [
                                'type' => 'keyword',
                            ],
                            'created_at' => [
                                'type' => 'date',
                                'format' => 'yyyy-MM-dd HH:mm:ss',
                            ],
                        ],
                    ];
                }
                $callRequest = new CallRequestModel();
                $callRequest->setMethod('PUT');
                $callRequest->setJson($json);
                $callRequest->setPath('/.elastictsearch-admin-users');
                $callResponse = $this->callManager->call($callRequest);

                $this->addFlash('info', json_encode($callResponse->getContent()));

                $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPasswordPlain()));

                $json = [
                    'email' => $user->getEmail(),
                    'password' => $user->getPassword(),
                    'roles' => [
                        'ROLE_ADMIN'
                    ],
                    'created_at' => (new \Datetime())->format('Y-m-d H:i:s'),
                ];
                $callRequest = new CallRequestModel();
                if (true == $this->callManager->checkVersion('6.2')) {
                    $callRequest->setPath('/.elastictsearch-admin-users/_doc/'.$user->getEmail());
                } else {
                    $callRequest->setPath('/.elastictsearch-admin-users/doc/'.$user->getEmail());
                }
                $callRequest->setMethod('POST');
                $callRequest->setJson($json);
                $callResponse = $this->callManager->call($callRequest);

                $this->addFlash('info', json_encode($callResponse->getContent()));

                return $this->redirectToRoute('app_login');
            } catch (CallException $e) {
                $this->addFlash('danger', $e->getMessage());
            }
        }

        return $this->renderAbstract($request, 'Modules/security/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
