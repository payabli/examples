<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;

class TemplateAdditionalDataSection extends JsonSerializableType
{
    /**
     * @var ?bool $visible
     */
    #[JsonProperty('visible')]
    public ?bool $visible;

    /**
     * @var array<string, TemplateAdditionalDataField> $fields
     */
    #[JsonProperty('fields'), ArrayType(['string' => TemplateAdditionalDataField::class])]
    public array $fields;

    /**
     * @param array{
     *   fields: array<string, TemplateAdditionalDataField>,
     *   visible?: ?bool,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->visible = $values['visible'] ?? null;
        $this->fields = $values['fields'];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
