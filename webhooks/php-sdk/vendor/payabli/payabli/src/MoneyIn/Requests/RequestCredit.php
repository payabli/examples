<?php

namespace Payabli\MoneyIn\Requests;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Types\PayorDataRequest;
use Payabli\Types\PaymentDetailCredit;
use Payabli\MoneyIn\Types\RequestCreditPaymentMethod;

class RequestCredit extends JsonSerializableType
{
    /**
     * @var ?bool $forceCustomerCreation
     */
    public ?bool $forceCustomerCreation;

    /**
     * @var ?string $idempotencyKey
     */
    public ?string $idempotencyKey;

    /**
     * @var ?string $accountId
     */
    #[JsonProperty('accountId')]
    public ?string $accountId;

    /**
     * @var PayorDataRequest $customerData Object describing the customer/payor.
     */
    #[JsonProperty('customerData')]
    public PayorDataRequest $customerData;

    /**
     * @var ?string $entrypoint
     */
    #[JsonProperty('entrypoint')]
    public ?string $entrypoint;

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
     * @var PaymentDetailCredit $paymentDetails
     */
    #[JsonProperty('paymentDetails')]
    public PaymentDetailCredit $paymentDetails;

    /**
     * @var RequestCreditPaymentMethod $paymentMethod Object describing the ACH payment method to use for transaction.
     */
    #[JsonProperty('paymentMethod')]
    public RequestCreditPaymentMethod $paymentMethod;

    /**
     * @var ?string $source
     */
    #[JsonProperty('source')]
    public ?string $source;

    /**
     * @var ?string $subdomain
     */
    #[JsonProperty('subdomain')]
    public ?string $subdomain;

    /**
     * @param array{
     *   customerData: PayorDataRequest,
     *   paymentDetails: PaymentDetailCredit,
     *   paymentMethod: RequestCreditPaymentMethod,
     *   forceCustomerCreation?: ?bool,
     *   idempotencyKey?: ?string,
     *   accountId?: ?string,
     *   entrypoint?: ?string,
     *   orderDescription?: ?string,
     *   orderId?: ?string,
     *   source?: ?string,
     *   subdomain?: ?string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->forceCustomerCreation = $values['forceCustomerCreation'] ?? null;
        $this->idempotencyKey = $values['idempotencyKey'] ?? null;
        $this->accountId = $values['accountId'] ?? null;
        $this->customerData = $values['customerData'];
        $this->entrypoint = $values['entrypoint'] ?? null;
        $this->orderDescription = $values['orderDescription'] ?? null;
        $this->orderId = $values['orderId'] ?? null;
        $this->paymentDetails = $values['paymentDetails'];
        $this->paymentMethod = $values['paymentMethod'];
        $this->source = $values['source'] ?? null;
        $this->subdomain = $values['subdomain'] ?? null;
    }
}
