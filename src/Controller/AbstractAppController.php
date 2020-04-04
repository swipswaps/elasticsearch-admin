<?php

namespace App\Controller;

use App\Manager\CallManager;
use App\Manager\PaginatorManager;
use App\Model\CallModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractAppController extends AbstractController
{
    /**
     * @required
     */
    public function setCallManager(CallManager $callManager)
    {
        $this->callManager = $callManager;
    }

    /**
     * @required
     */
    public function setPaginatorManager(PaginatorManager $paginatorManager)
    {
        $this->paginatorManager = $paginatorManager;
    }

    public function renderAbstract(Request $request, string $view, array $parameters = [], Response $response = null): Response
    {
        $call = new CallModel();
        $call->setPath('/_cluster/health');
        $clusterHealth = $this->callManager->call($call);

        $parameters['clusterHealth'] = $clusterHealth;

        $call = new CallModel();
        $call->setPath('/_cluster/state');
        $clusterState = $this->callManager->call($call);

        $nodes = [];
        foreach ($clusterState['nodes'] as $k => $v) {
            $nodes[$k] = $v['name'];
        }

        $parameters['master_node'] = $nodes[$clusterState['master_node']] ?? false;

        /*if (null === $response) {
            $response = new Response();
        }
        $response->headers->set('Symfony-Debug-Toolbar-Replace', 1);*/

        return $this->render($view, $parameters, $response);
    }
}
