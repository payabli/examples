<?php

namespace Payabli\MoneyIn\Requests;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Types\RefundDetail;

class RequestRefund extends JsonSerializableType
{
    /**
     * @var ?string $idempotencyKey
     */
    public ?string $idempotencyKey;

    /**
     *
     * Amount to refund from original transaction, minus any service fees charged on the original transaction.
     *
     * The amount provided can't be greater than the original total amount of the transaction, minus service fees. For example, if a transaction was $90 plus a $10 service fee, you can refund up to $90.
     *
     * An amount equal to zero will refund the total amount authorized minus any service fee.
     *
     * @var ?float $amount
     */
    #[JsonProperty('amount')]
    public ?float $amount;

    /**
     * @var ?string $ipaddress
     */
    #[JsonProperty('ipaddress')]
    public ?string $ipaddress;

    /**
     * @var ?string $orderDescription
     */
    #[JsonProperty('orderDescription')]
    public ?string $orderDescription;

    /**
     * @var ?string $orderId
     */
    #[JsonProperty('orderId')]
    public ?string $orderId;

    /**
     * @var ?RefundDetail $refundDetails
     */
    #[JsonProperty('refundDetails')]
    public ?RefundDetail $refundDetails;

    /**
     * @var ?string $source
     */
    #[JsonProperty('source')]
    public ?string $source;

    /**
     * @param array{
     *   idempotencyKey?: ?string,
     *   amount?: ?float,
     *   ipaddress?: ?string,
     *   orderDescription?: ?string,
     *   orderId?: ?string,
     *   refundDetails?: ?RefundDetail,
     *   source?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->idempotencyKey = $values['idempotencyKey'] ?? null;
        $this->amount = $values['amount'] ?? null;
        $this->ipaddress = $values['ipaddress'] ?? null;
        $this->orderDescription = $values['orderDescription'] ?? null;
        $this->orderId = $values['orderId'] ?? null;
        $this->refundDetails = $values['refundDetails'] ?? null;
        $this->source = $values['source'] ?? null;
    }
}
