<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class OrganizationQueryRecordServicesItem extends JsonSerializableType
{
    /**
     * @var ?string $description
     */
    #[JsonProperty('description')]
    public ?string $description;

    /**
     * @var ?bool $enabled
     */
    #[JsonProperty('enabled')]
    public ?bool $enabled;

    /**
     * @var ?float $monthlyCost
     */
    #[JsonProperty('monthlyCost')]
    public ?float $monthlyCost;

    /**
     * @var ?string $name
     */
    #[JsonProperty('name')]
    public ?string $name;

    /**
     * @var ?bool $reseller
     */
    #[JsonProperty('reseller')]
    public ?bool $reseller;

    /**
     * @var ?float $setupCost
     */
    #[JsonProperty('setupCost')]
    public ?float $setupCost;

    /**
     * @var ?float $txCost
     */
    #[JsonProperty('txCost')]
    public ?float $txCost;

    /**
     * @var ?float $txPercentCost
     */
    #[JsonProperty('txPercentCost')]
    public ?float $txPercentCost;

    /**
     * @param array{
     *   description?: ?string,
     *   enabled?: ?bool,
     *   monthlyCost?: ?float,
     *   name?: ?string,
     *   reseller?: ?bool,
     *   setupCost?: ?float,
     *   txCost?: ?float,
     *   txPercentCost?: ?float,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->description = $values['description'] ?? null;
        $this->enabled = $values['enabled'] ?? null;
        $this->monthlyCost = $values['monthlyCost'] ?? null;
        $this->name = $values['name'] ?? null;
        $this->reseller = $values['reseller'] ?? null;
        $this->setupCost = $values['setupCost'] ?? null;
        $this->txCost = $values['txCost'] ?? null;
        $this->txPercentCost = $values['txPercentCost'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
