<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class BillingFeeDetail extends JsonSerializableType
{
    /**
     * @var ?string $billableEvent
     */
    #[JsonProperty('billableEvent')]
    public ?string $billableEvent;

    /**
     * @var ?string $service
     */
    #[JsonProperty('service')]
    public ?string $service;

    /**
     * @var ?string $eventId
     */
    #[JsonProperty('eventId')]
    public ?string $eventId;

    /**
     * @var ?string $description Description of the billing fee
     */
    #[JsonProperty('description')]
    public ?string $description;

    /**
     * @var ?string $category Category of the billing fee
     */
    #[JsonProperty('category')]
    public ?string $category;

    /**
     * @var ?float $fixPrice Fixed price component of the fee
     */
    #[JsonProperty('fixPrice')]
    public ?float $fixPrice;

    /**
     * @var ?float $floatPrice Percentage component of the fee
     */
    #[JsonProperty('floatPrice')]
    public ?float $floatPrice;

    /**
     * @var ?float $billableAmount Amount eligible for the fee
     */
    #[JsonProperty('billableAmount')]
    public ?float $billableAmount;

    /**
     * @var ?float $billAmount Total fee amount charged
     */
    #[JsonProperty('billAmount')]
    public ?float $billAmount;

    /**
     * @var ?string $frequency
     */
    #[JsonProperty('frequency')]
    public ?string $frequency;

    /**
     * @var ?string $serviceGroup
     */
    #[JsonProperty('serviceGroup')]
    public ?string $serviceGroup;

    /**
     * @param array{
     *   billableEvent?: ?string,
     *   service?: ?string,
     *   eventId?: ?string,
     *   description?: ?string,
     *   category?: ?string,
     *   fixPrice?: ?float,
     *   floatPrice?: ?float,
     *   billableAmount?: ?float,
     *   billAmount?: ?float,
     *   frequency?: ?string,
     *   serviceGroup?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->billableEvent = $values['billableEvent'] ?? null;
        $this->service = $values['service'] ?? null;
        $this->eventId = $values['eventId'] ?? null;
        $this->description = $values['description'] ?? null;
        $this->category = $values['category'] ?? null;
        $this->fixPrice = $values['fixPrice'] ?? null;
        $this->floatPrice = $values['floatPrice'] ?? null;
        $this->billableAmount = $values['billableAmount'] ?? null;
        $this->billAmount = $values['billAmount'] ?? null;
        $this->frequency = $values['frequency'] ?? null;
        $this->serviceGroup = $values['serviceGroup'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
