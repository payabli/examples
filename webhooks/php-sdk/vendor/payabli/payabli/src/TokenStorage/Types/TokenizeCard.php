<?php

namespace Payabli\TokenStorage\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class TokenizeCard extends JsonSerializableType
{
    /**
     * @var string $method The type of payment method to tokenize. For cards, this is always `card`.
     */
    #[JsonProperty('method')]
    public string $method;

    /**
     * @var ?string $cardcvv
     */
    #[JsonProperty('cardcvv')]
    public ?string $cardcvv;

    /**
     * @var string $cardexp
     */
    #[JsonProperty('cardexp')]
    public string $cardexp;

    /**
     * @var string $cardHolder
     */
    #[JsonProperty('cardHolder')]
    public string $cardHolder;

    /**
     * @var string $cardnumber
     */
    #[JsonProperty('cardnumber')]
    public string $cardnumber;

    /**
     * @var ?string $cardzip
     */
    #[JsonProperty('cardzip')]
    public ?string $cardzip;

    /**
     * @param array{
     *   method: string,
     *   cardexp: string,
     *   cardHolder: string,
     *   cardnumber: string,
     *   cardcvv?: ?string,
     *   cardzip?: ?string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->method = $values['method'];
        $this->cardcvv = $values['cardcvv'] ?? null;
        $this->cardexp = $values['cardexp'];
        $this->cardHolder = $values['cardHolder'];
        $this->cardnumber = $values['cardnumber'];
        $this->cardzip = $values['cardzip'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
