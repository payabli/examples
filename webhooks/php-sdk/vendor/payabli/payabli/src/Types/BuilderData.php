<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class BuilderData extends JsonSerializableType
{
    /**
     * @var ?SSection $services
     */
    #[JsonProperty('services')]
    public ?SSection $services;

    /**
     * @var ?ASection $attributes
     */
    #[JsonProperty('attributes')]
    public ?ASection $attributes;

    /**
     * @var ?DSection $banking
     */
    #[JsonProperty('banking')]
    public ?DSection $banking;

    /**
     * @var ?BSection $business
     */
    #[JsonProperty('business')]
    public ?BSection $business;

    /**
     * @var ?OSection $owners
     */
    #[JsonProperty('owners')]
    public ?OSection $owners;

    /**
     * @var ?PSection $processing
     */
    #[JsonProperty('processing')]
    public ?PSection $processing;

    /**
     * @param array{
     *   services?: ?SSection,
     *   attributes?: ?ASection,
     *   banking?: ?DSection,
     *   business?: ?BSection,
     *   owners?: ?OSection,
     *   processing?: ?PSection,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->services = $values['services'] ?? null;
        $this->attributes = $values['attributes'] ?? null;
        $this->banking = $values['banking'] ?? null;
        $this->business = $values['business'] ?? null;
        $this->owners = $values['owners'] ?? null;
        $this->processing = $values['processing'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
