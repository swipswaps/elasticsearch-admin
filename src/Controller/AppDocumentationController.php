<?php

namespace App\Controller;

use App\Controller\AbstractAppController;
use App\Exception\CallException;
use App\Form\ElasticsearchConsoleType;
use App\Model\CallRequestModel;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route("/admin")
 */
class AppDocumentationController extends AbstractAppController
{
    /**
     * @Route("/documentation/{page}", name="documentation")
     */
    public function index(Request $request, string $page): Response
    {
        //$this->denyAccessUnlessGranted('CONSOLE', 'global');

        $pages = [
            'ES_VERSION_TRACKING',
        ];

        if (false == in_array($page, $pages)) {
            throw new NotFoundHttpException();
        }

        return $this->renderAbstract($request, 'Modules/documentation/documentation_index.html.twig', [
            'page' => $page,
            'markdown' => file_get_contents(__DIR__.'/../../documentation/'.$page.'.md'),
        ]);
    }
}
