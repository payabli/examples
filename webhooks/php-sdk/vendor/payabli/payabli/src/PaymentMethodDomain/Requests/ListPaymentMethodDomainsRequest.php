<?php

namespace Payabli\PaymentMethodDomain\Requests;

use Payabli\Core\Json\JsonSerializableType;

class ListPaymentMethodDomainsRequest extends JsonSerializableType
{
    /**
     * Identifier for the organization or paypoint.
     * - For organization, provide the organization ID - For paypoint, provide the paypoint ID
     *
     * @var ?int $entityId
     */
    public ?int $entityId;

    /**
     * The type of entity. Valid values:
     *   - organization
     *   - paypoint
     *   - psp
     *
     * @var ?string $entityType
     */
    public ?string $entityType;

    /**
     * @var ?int $fromRecord Number of records to skip. Defaults to `0`.
     */
    public ?int $fromRecord;

    /**
     * @var ?int $limitRecord Max number of records for query response. Defaults to `20`.
     */
    public ?int $limitRecord;

    /**
     * @param array{
     *   entityId?: ?int,
     *   entityType?: ?string,
     *   fromRecord?: ?int,
     *   limitRecord?: ?int,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->entityId = $values['entityId'] ?? null;
        $this->entityType = $values['entityType'] ?? null;
        $this->fromRecord = $values['fromRecord'] ?? null;
        $this->limitRecord = $values['limitRecord'] ?? null;
    }
}
