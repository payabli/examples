<?php

namespace Payabli\MoneyOutTypes\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;

class AuthorizePayoutBody extends JsonSerializableType
{
    /**
     * @var string $entryPoint
     */
    #[JsonProperty('entryPoint')]
    public string $entryPoint;

    /**
     * @var ?string $source
     */
    #[JsonProperty('source')]
    public ?string $source;

    /**
     * @var ?string $orderId
     */
    #[JsonProperty('orderId')]
    public ?string $orderId;

    /**
     * @var ?string $orderDescription
     */
    #[JsonProperty('orderDescription')]
    public ?string $orderDescription;

    /**
     * @var AuthorizePaymentMethod $paymentMethod
     */
    #[JsonProperty('paymentMethod')]
    public AuthorizePaymentMethod $paymentMethod;

    /**
     * @var RequestOutAuthorizePaymentDetails $paymentDetails Object containing payment details.
     */
    #[JsonProperty('paymentDetails')]
    public RequestOutAuthorizePaymentDetails $paymentDetails;

    /**
     * @var RequestOutAuthorizeVendorData $vendorData Object containing vendor data.
     */
    #[JsonProperty('vendorData')]
    public RequestOutAuthorizeVendorData $vendorData;

    /**
     * @var array<RequestOutAuthorizeInvoiceData> $invoiceData Array of bills associated to the transaction
     */
    #[JsonProperty('invoiceData'), ArrayType([RequestOutAuthorizeInvoiceData::class])]
    public array $invoiceData;

    /**
     * @var ?string $accountId
     */
    #[JsonProperty('accountId')]
    public ?string $accountId;

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
     *   entryPoint: string,
     *   paymentMethod: AuthorizePaymentMethod,
     *   paymentDetails: RequestOutAuthorizePaymentDetails,
     *   vendorData: RequestOutAuthorizeVendorData,
     *   invoiceData: array<RequestOutAuthorizeInvoiceData>,
     *   source?: ?string,
     *   orderId?: ?string,
     *   orderDescription?: ?string,
     *   accountId?: ?string,
     *   subdomain?: ?string,
     *   subscriptionId?: ?int,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->entryPoint = $values['entryPoint'];
        $this->source = $values['source'] ?? null;
        $this->orderId = $values['orderId'] ?? null;
        $this->orderDescription = $values['orderDescription'] ?? null;
        $this->paymentMethod = $values['paymentMethod'];
        $this->paymentDetails = $values['paymentDetails'];
        $this->vendorData = $values['vendorData'];
        $this->invoiceData = $values['invoiceData'];
        $this->accountId = $values['accountId'] ?? null;
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
