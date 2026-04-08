<?php

namespace Payabli\TokenStorage\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Types\PayorDataRequest;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\Union;

class RequestTokenStorage extends JsonSerializableType
{
    /**
     * @var ?PayorDataRequest $customerData Object describing the Customer/Payor owner of payment method. Required for POST requests. Which fields are required depends on the paypoint's custom identifier settings.
     */
    #[JsonProperty('customerData')]
    public ?PayorDataRequest $customerData;

    /**
     * @var ?string $entryPoint Entrypoint identifier. Required for POST requests.
     */
    #[JsonProperty('entryPoint')]
    public ?string $entryPoint;

    /**
     * @var ?bool $fallbackAuth When `true`, if tokenization fails, Payabli will attempt an authorization transaction to request a permanent token for the card. If the authorization is successful, the card will be tokenized and the authorization will be voided automatically.
     */
    #[JsonProperty('fallbackAuth')]
    public ?bool $fallbackAuth;

    /**
     * @var ?int $fallbackAuthAmount The amount for the `fallbackAuth` transaction. Defaults to one dollar (`100`).
     */
    #[JsonProperty('fallbackAuthAmount')]
    public ?int $fallbackAuthAmount;

    /**
     * @var ?string $methodDescription Custom description for stored payment method.
     */
    #[JsonProperty('methodDescription')]
    public ?string $methodDescription;

    /**
     * @var (
     *    TokenizeCard
     *   |TokenizeAch
     *   |ConvertToken
     * )|null $paymentMethod Information about the payment method for the transaction.
     */
    #[JsonProperty('paymentMethod'), Union(TokenizeCard::class, TokenizeAch::class, ConvertToken::class, 'null')]
    public TokenizeCard|TokenizeAch|ConvertToken|null $paymentMethod;

    /**
     * @var ?string $source Custom identifier to indicate the source for the request
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
     *   customerData?: ?PayorDataRequest,
     *   entryPoint?: ?string,
     *   fallbackAuth?: ?bool,
     *   fallbackAuthAmount?: ?int,
     *   methodDescription?: ?string,
     *   paymentMethod?: (
     *    TokenizeCard
     *   |TokenizeAch
     *   |ConvertToken
     * )|null,
     *   source?: ?string,
     *   subdomain?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->customerData = $values['customerData'] ?? null;
        $this->entryPoint = $values['entryPoint'] ?? null;
        $this->fallbackAuth = $values['fallbackAuth'] ?? null;
        $this->fallbackAuthAmount = $values['fallbackAuthAmount'] ?? null;
        $this->methodDescription = $values['methodDescription'] ?? null;
        $this->paymentMethod = $values['paymentMethod'] ?? null;
        $this->source = $values['source'] ?? null;
        $this->subdomain = $values['subdomain'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
