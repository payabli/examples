<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class TemplateAdditionalDataField extends JsonSerializableType
{
    /**
     * @var ?bool $visible
     */
    #[JsonProperty('visible')]
    public ?bool $visible;

    /**
     * @var ?bool $readOnly
     */
    #[JsonProperty('readOnly')]
    public ?bool $readOnly;

    /**
     * @var ?bool $required
     */
    #[JsonProperty('required')]
    public ?bool $required;

    /**
     * @var ?int $posRow
     */
    #[JsonProperty('posRow')]
    public ?int $posRow;

    /**
     * @var ?int $posCol
     */
    #[JsonProperty('posCol')]
    public ?int $posCol;

    /**
     * @var ?string $value
     */
    #[JsonProperty('value')]
    public ?string $value;

    /**
     * @var ?string $label
     */
    #[JsonProperty('label')]
    public ?string $label;

    /**
     * @var ?string $type
     */
    #[JsonProperty('type')]
    public ?string $type;

    /**
     * @param array{
     *   visible?: ?bool,
     *   readOnly?: ?bool,
     *   required?: ?bool,
     *   posRow?: ?int,
     *   posCol?: ?int,
     *   value?: ?string,
     *   label?: ?string,
     *   type?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->visible = $values['visible'] ?? null;
        $this->readOnly = $values['readOnly'] ?? null;
        $this->required = $values['required'] ?? null;
        $this->posRow = $values['posRow'] ?? null;
        $this->posCol = $values['posCol'] ?? null;
        $this->value = $values['value'] ?? null;
        $this->label = $values['label'] ?? null;
        $this->type = $values['type'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
