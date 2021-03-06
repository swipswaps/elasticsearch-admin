<?php

namespace App\Tests\Controller;

use App\Model\CallRequestModel;

/**
 * @Route("/admin")
 */
class ElasticsearchClusterControllerTest extends AbstractAppControllerTest
{
    /**
     * @Route("/cluster", name="cluster")
     */
    public function testRead()
    {
        $callRequest = new CallRequestModel();
        $callRequest->setPath('/_cluster/health');
        $callResponse = $this->callManager->call($callRequest);
        $clusterHealth = $callResponse->getContent();

        $this->client->request('GET', '/admin/cluster');

        $this->assertResponseStatusCodeSame(200);
        $this->assertPageTitleSame('Cluster - '.$clusterHealth['cluster_name']);
    }

    /**
     * @Route("/cluster/allocation/explain", name="cluster_allocation_explain")
     */
    public function testAllocationExplain()
    {
        $callRequest = new CallRequestModel();
        $callRequest->setPath('/_cluster/health');
        $callResponse = $this->callManager->call($callRequest);
        $clusterHealth = $callResponse->getContent();

        $this->client->request('GET', '/admin/cluster/allocation/explain');

        if (false == $this->callManager->hasFeature('allocation_explain')) {
            $this->assertResponseStatusCodeSame(403);
        } else {
            $this->assertResponseStatusCodeSame(200);
            $this->assertPageTitleSame('Cluster - '.$clusterHealth['cluster_name'].' - Allocation explain');
        }
    }

    /**
     * @Route("/cluster/settings", name="cluster_settings")
     */
    public function testSettings()
    {
        $callRequest = new CallRequestModel();
        $callRequest->setPath('/_cluster/health');
        $callResponse = $this->callManager->call($callRequest);
        $clusterHealth = $callResponse->getContent();

        $this->client->request('GET', '/admin/cluster/settings');

        if (false == $this->callManager->hasFeature('cluster_settings')) {
            $this->assertResponseStatusCodeSame(403);
        } else {
            $this->assertResponseStatusCodeSame(200);
            $this->assertPageTitleSame('Cluster - '.$clusterHealth['cluster_name'].' - Settings');
        }
    }

    /**
     * @Route("/cluster/settings/{type}/{setting}/edit", name="cluster_settings_edit")
     */
    public function testEditPersistent()
    {
        $this->client->request('GET', '/admin/cluster/settings/persistent/cluster.routing.allocation.disk.watermark.low/edit');

        if (false == $this->callManager->hasFeature('cluster_settings')) {
            $this->assertResponseStatusCodeSame(403);
        } else {
            $this->assertResponseStatusCodeSame(200);
            $this->assertPageTitleSame('Cluster - cluster.routing.allocation.disk.watermark.low');
        }
    }

    public function testEditTransient()
    {
        $this->client->request('GET', '/admin/cluster/settings/transient/cluster.routing.allocation.disk.watermark.low/edit');

        if (false == $this->callManager->hasFeature('cluster_settings')) {
            $this->assertResponseStatusCodeSame(403);
        } else {
            $this->assertResponseStatusCodeSame(200);
            $this->assertPageTitleSame('Cluster - cluster.routing.allocation.disk.watermark.low');
        }
    }

    /**
     * @Route("/cluster/audit", name="cluster_audit")
     */
    public function testAudit()
    {
        $callRequest = new CallRequestModel();
        $callRequest->setPath('/_cluster/health');
        $callResponse = $this->callManager->call($callRequest);
        $clusterHealth = $callResponse->getContent();

        $this->client->request('GET', '/admin/cluster/audit');

        $this->assertResponseStatusCodeSame(200);
        $this->assertPageTitleSame('Cluster - '.$clusterHealth['cluster_name'].' - Audit');
    }

    /**
     * @Route("/cluster/disk-thresholds", name="cluster_disk_thresholds")
     */
    public function testDiskThresholds()
    {
        $callRequest = new CallRequestModel();
        $callRequest->setPath('/_cluster/health');
        $callResponse = $this->callManager->call($callRequest);
        $clusterHealth = $callResponse->getContent();

        $this->client->request('GET', '/admin/cluster/disk-thresholds');

        if (false == $this->callManager->hasFeature('cluster_settings')) {
            $this->assertResponseStatusCodeSame(403);
        } else {
            $this->assertResponseStatusCodeSame(200);
            $this->assertPageTitleSame('Cluster - '.$clusterHealth['cluster_name'].' - Disk thresholds');
        }
    }
}
