<?php

namespace App\Model;

use App\Model\AbstractAppModel;

class ElasticsearchIndexModel extends AbstractAppModel
{
    private $name;

    private $settings;

    private $mappings;

    public function __construct()
    {
        foreach ($this->getStaticSettings() as $staticSetting => $defaultValue) {
            $this->setSetting($staticSetting, $defaultValue);
        }

        foreach ($this->getDynamicSettings() as $dynamicSetting => $defaultValue) {
            $this->setSetting($dynamicSetting, $defaultValue);
        }
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSettings(): ?array
    {
        return $this->settings;
    }

    public function setSettings(?array $settings): self
    {
        $this->settings = $settings;

        return $this;
    }

    public function getSetting(?string $key): ?string
    {
        return true == isset($this->settings[$key]) ? $this->settings[$key] : false;
    }

    public function setSetting(string $key, string $value): self
    {
        $this->settings[$key] = $value;

        return $this;
    }

    public function getMappings(): ?string
    {
        return $this->mappings;
    }

    public function setMappings(?string $mappings): self
    {
        $this->mappings = $mappings;

        return $this;
    }

    public function getStaticSettings(): ?array
    {
        return [
            'index.number_of_shards' => '1',
            //'index.shard.check_on_startup' => '',
            //'index.codec' => '',
            //'index.routing_partition_size' => '',
            //'index.load_fixed_bitset_filters_eagerly' => '',
            //'index.hidden' => '',
        ];
    }

    public function getDynamicSettings(): ?array
    {
        return [
            'index.number_of_replicas' => '',
            'index.auto_expand_replicas' => '0-1',
            //'index.search.idle.after' => '',
            'index.refresh_interval' => '',
            'index.max_result_window' => '',
            //'index.max_inner_result_window' => '',
            //'index.max_rescore_window' => '',
            //'index.max_docvalue_fields_search' => '',
            //'index.max_script_fields' => '',
            //'index.max_script_fields' => '',
            //'index.max_shingle_diff' => '',
            //'index.blocks.read_only' => '',
            //'index.blocks.read_only_allow_delete' => '',
            //'index.blocks.read' => '',
            //'index.blocks.write' => '',
            //'index.blocks.metadata' => '',
            //'index.max_refresh_listeners' => '',
            //'index.analyze.max_token_count' => '',
            //'index.highlight.max_analyzed_offset' => '',
            //'index.max_terms_count' => '',
            //'index.max_regex_length' => '',
            //'index.routing.allocation.enable' => '',
            //'index.routing.rebalance.enable' => '',
            //'index.gc_deletes' => '',
            'index.default_pipeline' => '',
            'index.final_pipeline' => '',
        ];
    }

    public function convert(?array $index): self
    {
        $this->setName($index['index']);
        if (true == isset($index['mappings']) && 0 < count($index['mappings'])) {
            $this->setMappings(json_encode($index['mappings'], JSON_PRETTY_PRINT));
        }
        if (true == isset($index['settings']) && 0 < count($index['settings'])) {
            $this->setSettings($index['settings']);
        }
        return $this;
    }
}
