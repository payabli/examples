<?php

namespace Payabli\MoneyIn\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

/**
 * Object describing payment method to use for validation.
 */
class RequestPaymentValidatePaymentMethod extends JsonSerializableType
{
    /**
     * @var value-of<RequestPaymentValidatePaymentMethodMethod> $method
     */
    #[JsonProperty('method')]
    public string $method;

    /**
     * @var string $cardnumber
     */
    #[JsonProperty('cardnumber')]
    public string $cardnumber;

    /**
     * @var string $cardexp
     */
    #[JsonProperty('cardexp')]
    public string $cardexp;

    /**
     * @var string $cardzip
     */
    #[JsonProperty('cardzip')]
    public string $cardzip;

    /**
     * @var string $cardHolder
     */
    #[JsonProperty('cardHolder')]
    public string $cardHolder;

    /**
     * @param array{
     *   method: value-of<RequestPaymentValidatePaymentMethodMethod>,
     *   cardnumber: string,
     *   cardexp: string,
     *   cardzip: string,
     *   cardHolder: string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->method = $values['method'];
        $this->cardnumber = $values['cardnumber'];
        $this->cardexp = $values['cardexp'];
        $this->cardzip = $values['cardzip'];
        $this->cardHolder = $values['cardHolder'];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
