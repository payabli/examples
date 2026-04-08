<?php

namespace Payabli\MoneyIn\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Types\PayorDataRequest;
use Payabli\Types\BillData;
use Payabli\Types\PaymentDetail;
use Payabli\Types\PayMethodCredit;
use Payabli\Types\PayMethodAch;
use Payabli\Types\PayMethodStoredMethod;
use Payabli\Types\PayMethodCloud;
use Payabli\Types\Check;
use Payabli\Types\Cash;
use Payabli\Types\PayMethodBodyAllFields;
use Payabli\Core\Types\Union;

class TransRequestBody extends JsonSerializableType
{
    /**
     * @var ?string $accountId
     */
    #[JsonProperty('accountId')]
    public ?string $accountId;

    /**
     * @var ?PayorDataRequest $customerData Object describing the Customer/Payor. Which fields are required depends on the paypoint's custom identifier settings.
     */
    #[JsonProperty('customerData')]
    public ?PayorDataRequest $customerData;

    /**
     * @var ?string $entryPoint
     */
    #[JsonProperty('entryPoint')]
    public ?string $entryPoint;

    /**
     * @var ?BillData $invoiceData Object describing an Invoice linked to the transaction.
     */
    #[JsonProperty('invoiceData')]
    public ?BillData $invoiceData;

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
     * @var PaymentDetail $paymentDetails Object describing details of the payment. Required.
     */
    #[JsonProperty('paymentDetails')]
    public PaymentDetail $paymentDetails;

    /**
     * @var (
     *    PayMethodCredit
     *   |PayMethodAch
     *   |PayMethodStoredMethod
     *   |PayMethodCloud
     *   |Check
     *   |Cash
     *   |PayMethodBodyAllFields
     * ) $paymentMethod Information about the payment method for the transaction. Required and recommended fields for each payment method type are described in each schema below.
     */
    #[JsonProperty('paymentMethod'), Union(PayMethodCredit::class, PayMethodAch::class, PayMethodStoredMethod::class, PayMethodCloud::class, Check::class, Cash::class, PayMethodBodyAllFields::class)]
    public PayMethodCredit|PayMethodAch|PayMethodStoredMethod|PayMethodCloud|Check|Cash|PayMethodBodyAllFields $paymentMethod;

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
     * @var ?int $subscriptionId
     */
    #[JsonProperty('subscriptionId')]
    public ?int $subscriptionId;

    /**
     * @param array{
     *   paymentDetails: PaymentDetail,
     *   paymentMethod: (
     *    PayMethodCredit
     *   |PayMethodAch
     *   |PayMethodStoredMethod
     *   |PayMethodCloud
     *   |Check
     *   |Cash
     *   |PayMethodBodyAllFields
     * ),
     *   accountId?: ?string,
     *   customerData?: ?PayorDataRequest,
     *   entryPoint?: ?string,
     *   invoiceData?: ?BillData,
     *   ipaddress?: ?string,
     *   orderDescription?: ?string,
     *   orderId?: ?string,
     *   source?: ?string,
     *   subdomain?: ?string,
     *   subscriptionId?: ?int,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->accountId = $values['accountId'] ?? null;
        $this->customerData = $values['customerData'] ?? null;
        $this->entryPoint = $values['entryPoint'] ?? null;
        $this->invoiceData = $values['invoiceData'] ?? null;
        $this->ipaddress = $values['ipaddress'] ?? null;
        $this->orderDescription = $values['orderDescription'] ?? null;
        $this->orderId = $values['orderId'] ?? null;
        $this->paymentDetails = $values['paymentDetails'];
        $this->paymentMethod = $values['paymentMethod'];
        $this->source = $values['source'] ?? null;
        $this->subdomain = $values['subdomain'] ?? null;
        $this->subscriptionId = $values['subscriptionId'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
