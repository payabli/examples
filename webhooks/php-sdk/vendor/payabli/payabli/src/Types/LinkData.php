<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class LinkData extends JsonSerializableType
{
    /**
     * @var ?bool $ro
     */
    #[JsonProperty('ro')]
    public ?bool $ro;

    /**
     * @var ?bool $rq
     */
    #[JsonProperty('rq')]
    public ?bool $rq;

    /**
     * The type of validation applied to the field. Available values:
     * - text
     * - alpha
     * - ein
     * - url
     * - phone
     * - alphanumeric
     * - zipcode
     * - numbers
     * - float
     * - ssn
     * - email
     * - routing
     *
     * @var ?string $validator
     */
    #[JsonProperty('validator')]
    public ?string $validator;

    /**
     * @var ?string $value
     */
    #[JsonProperty('value')]
    public ?string $value;

    /**
     * @param array{
     *   ro?: ?bool,
     *   rq?: ?bool,
     *   validator?: ?string,
     *   value?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->ro = $values['ro'] ?? null;
        $this->rq = $values['rq'] ?? null;
        $this->validator = $values['validator'] ?? null;
        $this->value = $values['value'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
