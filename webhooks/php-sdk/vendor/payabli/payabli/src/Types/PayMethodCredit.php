<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class PayMethodCredit extends JsonSerializableType
{
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
     * @var ?string $cardHolder
     */
    #[JsonProperty('cardHolder')]
    public ?string $cardHolder;

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
     * @var ?string $initiator
     */
    #[JsonProperty('initiator')]
    public ?string $initiator;

    /**
     * @var 'card' $method Method to use for the transaction. For transactions with a credit or debit card, or a tokenized card, use `card`.
     */
    #[JsonProperty('method')]
    public string $method;

    /**
     * @var ?bool $saveIfSuccess
     */
    #[JsonProperty('saveIfSuccess')]
    public ?bool $saveIfSuccess;

    /**
     * @param array{
     *   cardexp: string,
     *   cardnumber: string,
     *   method: 'card',
     *   cardcvv?: ?string,
     *   cardHolder?: ?string,
     *   cardzip?: ?string,
     *   initiator?: ?string,
     *   saveIfSuccess?: ?bool,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->cardcvv = $values['cardcvv'] ?? null;
        $this->cardexp = $values['cardexp'];
        $this->cardHolder = $values['cardHolder'] ?? null;
        $this->cardnumber = $values['cardnumber'];
        $this->cardzip = $values['cardzip'] ?? null;
        $this->initiator = $values['initiator'] ?? null;
        $this->method = $values['method'];
        $this->saveIfSuccess = $values['saveIfSuccess'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
