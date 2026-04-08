<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class QueryResponseItemsRecordsItem extends JsonSerializableType
{
    /**
     * @var ?LineItem $lineItem
     */
    #[JsonProperty('LineItem')]
    public ?LineItem $lineItem;

    /**
     * @var ?string $parentOrgName
     */
    #[JsonProperty('ParentOrgName')]
    public ?string $parentOrgName;

    /**
     * @var ?string $paypointDbaname The paypoint's DBA name.
     */
    #[JsonProperty('PaypointDbaname')]
    public ?string $paypointDbaname;

    /**
     * @var ?string $paypointEntryname The paypoint's entry name (entrypoint).
     */
    #[JsonProperty('PaypointEntryname')]
    public ?string $paypointEntryname;

    /**
     * @var ?string $paypointLegalname the Paypoint's legal name.
     */
    #[JsonProperty('PaypointLegalname')]
    public ?string $paypointLegalname;

    /**
     * @param array{
     *   lineItem?: ?LineItem,
     *   parentOrgName?: ?string,
     *   paypointDbaname?: ?string,
     *   paypointEntryname?: ?string,
     *   paypointLegalname?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->lineItem = $values['lineItem'] ?? null;
        $this->parentOrgName = $values['parentOrgName'] ?? null;
        $this->paypointDbaname = $values['paypointDbaname'] ?? null;
        $this->paypointEntryname = $values['paypointEntryname'] ?? null;
        $this->paypointLegalname = $values['paypointLegalname'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
