<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class SummaryOrg extends JsonSerializableType
{
    /**
     * @var ?float $amountSubs
     */
    #[JsonProperty('amountSubs')]
    public ?float $amountSubs;

    /**
     * @var ?float $amountTx
     */
    #[JsonProperty('amountTx')]
    public ?float $amountTx;

    /**
     * @var ?int $childOrgs
     */
    #[JsonProperty('childOrgs')]
    public ?int $childOrgs;

    /**
     * @var ?int $childPaypoints
     */
    #[JsonProperty('childPaypoints')]
    public ?int $childPaypoints;

    /**
     * @var ?int $countSubs
     */
    #[JsonProperty('countSubs')]
    public ?int $countSubs;

    /**
     * @var ?int $countTx
     */
    #[JsonProperty('countTx')]
    public ?int $countTx;

    /**
     * @param array{
     *   amountSubs?: ?float,
     *   amountTx?: ?float,
     *   childOrgs?: ?int,
     *   childPaypoints?: ?int,
     *   countSubs?: ?int,
     *   countTx?: ?int,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->amountSubs = $values['amountSubs'] ?? null;
        $this->amountTx = $values['amountTx'] ?? null;
        $this->childOrgs = $values['childOrgs'] ?? null;
        $this->childPaypoints = $values['childPaypoints'] ?? null;
        $this->countSubs = $values['countSubs'] ?? null;
        $this->countTx = $values['countTx'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
