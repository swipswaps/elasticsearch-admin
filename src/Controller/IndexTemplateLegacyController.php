<?php

namespace App\Controller;

use App\Controller\AbstractAppController;
use App\Exception\CallException;
use App\Form\CreateIndexTemplateLegacyType;
use App\Manager\ElasticsearchIndexTemplateLegacyManager;
use App\Form\ConvertIndexTemplateLegacyType;
use App\Model\CallRequestModel;
use App\Model\ElasticsearchIndexTemplateLegacyModel;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * @Route("/admin")
 */
class IndexTemplateLegacyController extends AbstractAppController
{
    public function __construct(ElasticsearchIndexTemplateLegacyManager $elasticsearchIndexTemplateLegacyManager)
    {
        $this->elasticsearchIndexTemplateLegacyManager = $elasticsearchIndexTemplateLegacyManager;
    }

    /**
     * @Route("/index-templates-legacy", name="index_templates_legacy")
     */
    public function index(Request $request): Response
    {
        $this->denyAccessUnlessGranted('INDEX_TEMPLATES_LEGACY', 'global');

        $templates = $this->elasticsearchIndexTemplateLegacyManager->getAll();

        return $this->renderAbstract($request, 'Modules/index_template_legacy/index_template_legacy_index.html.twig', [
            'templates' => $this->paginatorManager->paginate([
                'route' => 'index_templates_legacy',
                'route_parameters' => [],
                'total' => count($templates),
                'rows' => $templates,
                'page' => 1,
                'size' => count($templates),
            ]),
        ]);
    }

    /**
     * @Route("/index-templates-legacy/convert", name="index_templates_legacy_convert")
     */
    public function convert(Request $request): Response
    {
        $form = $this->createForm(ConvertIndexTemplateLegacyType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $callRequest = new CallRequestModel();
            $callRequest->setPath('/_template');
            $callResponse = $this->callManager->call($callRequest);
            $indexTemplates = $callResponse->getContent();

            return $this->redirectToRoute('index_templates_legacy');
        }

        return $this->renderAbstract($request, 'Modules/index_template_legacy/index_template_legacy_convert.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/index-templates-legacy/create", name="index_templates_legacy_create")
     */
    public function create(Request $request): Response
    {
        $this->denyAccessUnlessGranted('INDEX_TEMPLATES_LEGACY_CREATE', 'global');

        $template = false;

        if ($request->query->get('template')) {
            $template = $this->elasticsearchIndexTemplateLegacyManager->getByName($request->query->get('template'));

            if (false == $template) {
                throw new NotFoundHttpException();
            }

            $this->denyAccessUnlessGranted('INDEX_TEMPLATE_LEGACY_COPY', $template);

            $template->setName($template->getName().'-copy');
        }

        if (false == $template) {
            $template = new ElasticsearchIndexTemplateLegacyModel();
        }
        $form = $this->createForm(CreateIndexTemplateLegacyType::class, $template);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $callResponse = $this->elasticsearchIndexTemplateLegacyManager->send($template);

                $this->addFlash('info', json_encode($callResponse->getContent()));

                return $this->redirectToRoute('index_templates_legacy_read', ['name' => $template->getName()]);
            } catch (CallException $e) {
                $this->addFlash('danger', $e->getMessage());
            }
        }

        return $this->renderAbstract($request, 'Modules/index_template_legacy/index_template_legacy_create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/index-templates-legacy/{name}", name="index_templates_legacy_read")
     */
    public function read(Request $request, string $name): Response
    {
        $this->denyAccessUnlessGranted('INDEX_TEMPLATES_LEGACY', 'global');

        $template = $this->elasticsearchIndexTemplateLegacyManager->getByName($name);

        if (false == $template) {
            throw new NotFoundHttpException();
        }

        return $this->renderAbstract($request, 'Modules/index_template_legacy/index_template_legacy_read.html.twig', [
            'template' => $template,
        ]);
    }

    /**
     * @Route("/index-templates-legacy/{name}/settings", name="index_templates_legacy_read_settings")
     */
    public function settings(Request $request, string $name): Response
    {
        $this->denyAccessUnlessGranted('INDEX_TEMPLATES_LEGACY', 'global');

        $template = $this->elasticsearchIndexTemplateLegacyManager->getByName($name);

        if (false == $template) {
            throw new NotFoundHttpException();
        }

        return $this->renderAbstract($request, 'Modules/index_template_legacy/index_template_legacy_read_settings.html.twig', [
            'template' => $template,
        ]);
    }

    /**
     * @Route("/index-templates-legacy/{name}/mappings", name="index_templates_legacy_read_mappings")
     */
    public function mappings(Request $request, string $name): Response
    {
        $this->denyAccessUnlessGranted('INDEX_TEMPLATES_LEGACY', 'global');

        $template = $this->elasticsearchIndexTemplateLegacyManager->getByName($name);

        if (false == $template) {
            throw new NotFoundHttpException();
        }

        return $this->renderAbstract($request, 'Modules/index_template_legacy/index_template_legacy_read_mappings.html.twig', [
            'template' => $template,
        ]);
    }

    /**
     * @Route("/index-templates-legacy/{name}/update", name="index_templates_legacy_update")
     */
    public function update(Request $request, string $name): Response
    {
        $template = $this->elasticsearchIndexTemplateLegacyManager->getByName($name);

        if (false == $template) {
            throw new NotFoundHttpException();
        }

        $this->denyAccessUnlessGranted('INDEX_TEMPLATE_LEGACY_UPDATE', $template);

        $form = $this->createForm(CreateIndexTemplateLegacyType::class, $template, ['context' => 'update']);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $callResponse = $this->elasticsearchIndexTemplateLegacyManager->send($template);

                $this->addFlash('info', json_encode($callResponse->getContent()));

                return $this->redirectToRoute('index_templates_legacy_read', ['name' => $template->getName()]);
            } catch (CallException $e) {
                $this->addFlash('danger', $e->getMessage());
            }
        }

        return $this->renderAbstract($request, 'Modules/index_template_legacy/index_template_legacy_update.html.twig', [
            'template' => $template,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/index-templates-legacy/{name}/delete", name="index_templates_legacy_delete")
     */
    public function delete(Request $request, string $name): Response
    {
        $template = $this->elasticsearchIndexTemplateLegacyManager->getByName($name);

        if (false == $template) {
            throw new NotFoundHttpException();
        }

        $this->denyAccessUnlessGranted('INDEX_TEMPLATE_LEGACY_DELETE', $template);

        $callResponse = $this->elasticsearchIndexTemplateLegacyManager->deleteByName($template->getName());

        $this->addFlash('info', json_encode($callResponse->getContent()));

        return $this->redirectToRoute('index_templates_legacy');
    }
}
