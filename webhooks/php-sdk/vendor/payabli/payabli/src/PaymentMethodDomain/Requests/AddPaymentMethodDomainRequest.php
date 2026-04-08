<?php

namespace Payabli\PaymentMethodDomain\Requests;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\PaymentMethodDomain\Types\AddPaymentMethodDomainRequestApplePay;
use Payabli\Core\Json\JsonProperty;
use Payabli\PaymentMethodDomain\Types\AddPaymentMethodDomainRequestGooglePay;

class AddPaymentMethodDomainRequest extends JsonSerializableType
{
    /**
     * @var ?AddPaymentMethodDomainRequestApplePay $applePay Apple Pay configuration information.
     */
    #[JsonProperty('applePay')]
    public ?AddPaymentMethodDomainRequestApplePay $applePay;

    /**
     * @var ?AddPaymentMethodDomainRequestGooglePay $googlePay Google Pay configuration information.
     */
    #[JsonProperty('googlePay')]
    public ?AddPaymentMethodDomainRequestGooglePay $googlePay;

    /**
     * @var ?string $domainName
     */
    #[JsonProperty('domainName')]
    public ?string $domainName;

    /**
     * @var ?int $entityId
     */
    #[JsonProperty('entityId')]
    public ?int $entityId;

    /**
     * @var ?string $entityType
     */
    #[JsonProperty('entityType')]
    public ?string $entityType;

    /**
     * @param array{
     *   applePay?: ?AddPaymentMethodDomainRequestApplePay,
     *   googlePay?: ?AddPaymentMethodDomainRequestGooglePay,
     *   domainName?: ?string,
     *   entityId?: ?int,
     *   entityType?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->applePay = $values['applePay'] ?? null;
        $this->googlePay = $values['googlePay'] ?? null;
        $this->domainName = $values['domainName'] ?? null;
        $this->entityId = $values['entityId'] ?? null;
        $this->entityType = $values['entityType'] ?? null;
    }
}
