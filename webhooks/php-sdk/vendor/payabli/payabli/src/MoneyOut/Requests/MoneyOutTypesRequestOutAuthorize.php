<?php

namespace Payabli\MoneyOut\Requests;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\MoneyOutTypes\Types\AuthorizePayoutBody;

class MoneyOutTypesRequestOutAuthorize extends JsonSerializableType
{
    /**
     * @var ?bool $allowDuplicatedBills When `true`, the authorization bypasses the requirement for unique bills, identified by vendor invoice number. This allows you to make more than one payout authorization for a bill, like a split payment.
     */
    public ?bool $allowDuplicatedBills;

    /**
     * @var ?bool $doNotCreateBills When `true`, Payabli won't automatically create a bill for this payout transaction.
     */
    public ?bool $doNotCreateBills;

    /**
     * @var ?bool $forceVendorCreation When `true`, the request creates a new vendor record, regardless of whether the vendor already exists.
     */
    public ?bool $forceVendorCreation;

    /**
     * @var ?string $idempotencyKey
     */
    public ?string $idempotencyKey;

    /**
     * @var AuthorizePayoutBody $body
     */
    public AuthorizePayoutBody $body;

    /**
     * @param array{
     *   body: AuthorizePayoutBody,
     *   allowDuplicatedBills?: ?bool,
     *   doNotCreateBills?: ?bool,
     *   forceVendorCreation?: ?bool,
     *   idempotencyKey?: ?string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->allowDuplicatedBills = $values['allowDuplicatedBills'] ?? null;
        $this->doNotCreateBills = $values['doNotCreateBills'] ?? null;
        $this->forceVendorCreation = $values['forceVendorCreation'] ?? null;
        $this->idempotencyKey = $values['idempotencyKey'] ?? null;
        $this->body = $values['body'];
    }
}
