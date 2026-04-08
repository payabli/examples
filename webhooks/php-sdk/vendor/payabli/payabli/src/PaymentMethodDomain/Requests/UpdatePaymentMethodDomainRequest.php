<?php

namespace Payabli\PaymentMethodDomain\Requests;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\PaymentMethodDomain\Types\UpdatePaymentMethodDomainRequestWallet;
use Payabli\Core\Json\JsonProperty;

class UpdatePaymentMethodDomainRequest extends JsonSerializableType
{
    /**
     * @var ?UpdatePaymentMethodDomainRequestWallet $applePay
     */
    #[JsonProperty('applePay')]
    public ?UpdatePaymentMethodDomainRequestWallet $applePay;

    /**
     * @var ?UpdatePaymentMethodDomainRequestWallet $googlePay
     */
    #[JsonProperty('googlePay')]
    public ?UpdatePaymentMethodDomainRequestWallet $googlePay;

    /**
     * @param array{
     *   applePay?: ?UpdatePaymentMethodDomainRequestWallet,
     *   googlePay?: ?UpdatePaymentMethodDomainRequestWallet,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->applePay = $values['applePay'] ?? null;
        $this->googlePay = $values['googlePay'] ?? null;
    }
}
