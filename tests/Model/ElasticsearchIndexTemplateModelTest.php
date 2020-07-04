<?php

namespace App\Tests\Model;

use App\Model\ElasticsearchIndexTemplateModel;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ElasticsearchIndexTemplateModelTest extends WebTestCase
{
    public function test()
    {
        $template = new ElasticsearchIndexTemplateModel();
        $template->setName('name');
        $template->setIndexPatterns('index-patterns');
        $template->setVersion(1);
        $template->setPriority(2);
        $template->setSettings([]);
        $template->setSetting('setting-key', 'setting-value');
        $template->setMappings([]);
        $template->setAliases([]);
        $template->setComposedOf([]);

        $this->assertEquals($template->getName(), 'name');

        $this->assertEquals($template->getIndexPatterns(), 'index-patterns');

        $this->assertEquals($template->getVersion(), 1);
        $this->assertIsInt($template->getVersion());

        $this->assertEquals($template->getPriority(), 2);
        $this->assertIsInt($template->getPriority());

        $this->assertEquals($template->getSettings(), ['setting-key' => 'setting-value']);
        $this->assertIsArray($template->getSettings());
        $this->assertEquals($template->getSetting('setting-key'), 'setting-value');

        $this->assertEquals($template->getMappings(), []);
        $this->assertIsArray($template->getMappings());

        $this->assertEquals($template->getAliases(), []);
        $this->assertIsArray($template->getAliases());

        $this->assertEquals($template->getComposedOf(), []);
        $this->assertIsArray($template->getComposedOf());

        $this->assertEquals($template->isSystem(), false);

        $template->setName('.name');
        $this->assertEquals($template->isSystem(), true);
    }
}
