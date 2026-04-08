<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

/**
 * Model for the PaymentMethod object, includes all method types.
 */
class PayMethodBodyAllFields extends JsonSerializableType
{
    /**
     * @var string $achAccount Bank account number. This field is **required** when method = 'ach'.
     */
    #[JsonProperty('achAccount')]
    public string $achAccount;

    /**
     * @var ?value-of<Achaccounttype> $achAccountType
     */
    #[JsonProperty('achAccountType')]
    public ?string $achAccountType;

    /**
     * @var ?string $achCode
     */
    #[JsonProperty('achCode')]
    public ?string $achCode;

    /**
     * @var string $achHolder
     */
    #[JsonProperty('achHolder')]
    public string $achHolder;

    /**
     * @var string $achRouting ABA/routing number of Bank account. This field is **required** when method = 'ach'.
     */
    #[JsonProperty('achRouting')]
    public string $achRouting;

    /**
     * @var ?string $cardcvv
     */
    #[JsonProperty('cardcvv')]
    public ?string $cardcvv;

    /**
     * @var ?string $cardexp
     */
    #[JsonProperty('cardexp')]
    public ?string $cardexp;

    /**
     * @var ?string $cardHolder
     */
    #[JsonProperty('cardHolder')]
    public ?string $cardHolder;

    /**
     * @var ?string $cardnumber
     */
    #[JsonProperty('cardnumber')]
    public ?string $cardnumber;

    /**
     * @var ?string $cardzip
     */
    #[JsonProperty('cardzip')]
    public ?string $cardzip;

    /**
     * @var ?string $device
     */
    #[JsonProperty('device')]
    public ?string $device;

    /**
     * @var ?string $initator
     */
    #[JsonProperty('initator')]
    public ?string $initator;

    /**
     * @var ?value-of<Methodall> $method
     */
    #[JsonProperty('method')]
    public ?string $method;

    /**
     * @var ?bool $saveIfSuccess
     */
    #[JsonProperty('saveIfSuccess')]
    public ?bool $saveIfSuccess;

    /**
     * @var ?string $storedMethodId
     */
    #[JsonProperty('storedMethodId')]
    public ?string $storedMethodId;

    /**
     * @var ?string $storedMethodUsageType
     */
    #[JsonProperty('storedMethodUsageType')]
    public ?string $storedMethodUsageType;

    /**
     * @param array{
     *   achAccount: string,
     *   achHolder: string,
     *   achRouting: string,
     *   achAccountType?: ?value-of<Achaccounttype>,
     *   achCode?: ?string,
     *   cardcvv?: ?string,
     *   cardexp?: ?string,
     *   cardHolder?: ?string,
     *   cardnumber?: ?string,
     *   cardzip?: ?string,
     *   device?: ?string,
     *   initator?: ?string,
     *   method?: ?value-of<Methodall>,
     *   saveIfSuccess?: ?bool,
     *   storedMethodId?: ?string,
     *   storedMethodUsageType?: ?string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->achAccount = $values['achAccount'];
        $this->achAccountType = $values['achAccountType'] ?? null;
        $this->achCode = $values['achCode'] ?? null;
        $this->achHolder = $values['achHolder'];
        $this->achRouting = $values['achRouting'];
        $this->cardcvv = $values['cardcvv'] ?? null;
        $this->cardexp = $values['cardexp'] ?? null;
        $this->cardHolder = $values['cardHolder'] ?? null;
        $this->cardnumber = $values['cardnumber'] ?? null;
        $this->cardzip = $values['cardzip'] ?? null;
        $this->device = $values['device'] ?? null;
        $this->initator = $values['initator'] ?? null;
        $this->method = $values['method'] ?? null;
        $this->saveIfSuccess = $values['saveIfSuccess'] ?? null;
        $this->storedMethodId = $values['storedMethodId'] ?? null;
        $this->storedMethodUsageType = $values['storedMethodUsageType'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
