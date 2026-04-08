<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class PayorFields extends JsonSerializableType
{
    /**
     * @var ?bool $display Flag indicating if the input field will show in container
     */
    #[JsonProperty('display')]
    public ?bool $display;

    /**
     * @var ?bool $fixed Flag indicating if the value in input field is read-only or not.
     */
    #[JsonProperty('fixed')]
    public ?bool $fixed;

    /**
     * @var ?bool $identifier Flag indicating if the input field is a customer identifier
     */
    #[JsonProperty('identifier')]
    public ?bool $identifier;

    /**
     * @var ?string $label Label to display for field
     */
    #[JsonProperty('label')]
    public ?string $label;

    /**
     * @var ?string $name Name of field to show. Should be one of the standard customer fields or a custom field name
     */
    #[JsonProperty('name')]
    public ?string $name;

    /**
     * @var ?int $order
     */
    #[JsonProperty('order')]
    public ?int $order;

    /**
     * @var ?bool $required Flag indicating if the input field is required for validation
     */
    #[JsonProperty('required')]
    public ?bool $required;

    /**
     * Type of validation to apply to the input field Accepted values:
     *
     *   - alpha for alphabetical
     *
     *   - numbers for numeric
     *
     *   - text for alphanumeric
     *
     *   - email for masked email address input
     *
     *   - phone for US phone numbers
     *
     * @var ?string $validation
     */
    #[JsonProperty('validation')]
    public ?string $validation;

    /**
     * @var ?string $value Pre-populated value for field
     */
    #[JsonProperty('value')]
    public ?string $value;

    /**
     * Numeric value indicating the size of input relative to the container. Accepted values:
     *
     *     - 4 = 1/3
     *
     *     - 6 = 1/2
     *
     *     - 8 = 2/3
     *
     *     - 12 = 3/3
     *
     * @var ?int $width
     */
    #[JsonProperty('width')]
    public ?int $width;

    /**
     * @param array{
     *   display?: ?bool,
     *   fixed?: ?bool,
     *   identifier?: ?bool,
     *   label?: ?string,
     *   name?: ?string,
     *   order?: ?int,
     *   required?: ?bool,
     *   validation?: ?string,
     *   value?: ?string,
     *   width?: ?int,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->display = $values['display'] ?? null;
        $this->fixed = $values['fixed'] ?? null;
        $this->identifier = $values['identifier'] ?? null;
        $this->label = $values['label'] ?? null;
        $this->name = $values['name'] ?? null;
        $this->order = $values['order'] ?? null;
        $this->required = $values['required'] ?? null;
        $this->validation = $values['validation'] ?? null;
        $this->value = $values['value'] ?? null;
        $this->width = $values['width'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
