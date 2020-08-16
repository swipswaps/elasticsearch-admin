<?php

namespace App\Model;

use App\Model\AbstractAppModel;

class ElasticsearchClusterSettingModel extends AbstractAppModel
{
    private $type;

    private $setting;

    private $value;

    private $isArray;

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getSetting(): ?string
    {
        return $this->setting;
    }

    public function setSetting(?string $setting): self
    {
        $this->setting = $setting;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getIsArray(): ?bool
    {
        return $this->isArray;
    }

    public function setIsArray(bool $isArray): self
    {
        $this->value = $isArray;

        return $this;
    }

    public function getJson(): array
    {
        $value = $this->getValue();

        if (true === $this->getIsArray()) {
            $value = explode(',', $value);
        }

        $json = [
            $this->getType() => [
                $this->getSetting() => $value,
            ],
        ];

        return $json;
    }
}
