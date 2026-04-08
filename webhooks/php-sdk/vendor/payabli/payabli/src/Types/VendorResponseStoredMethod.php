<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use DateTime;
use Payabli\Core\Types\Date;

/**
 * Stored payment method information
 */
class VendorResponseStoredMethod extends JsonSerializableType
{
    /**
     * @var ?string $idPmethod
     */
    #[JsonProperty('IdPmethod')]
    public ?string $idPmethod;

    /**
     * @var ?string $method
     */
    #[JsonProperty('Method')]
    public ?string $method;

    /**
     * @var ?string $descriptor
     */
    #[JsonProperty('Descriptor')]
    public ?string $descriptor;

    /**
     * @var ?string $maskedAccount
     */
    #[JsonProperty('MaskedAccount')]
    public ?string $maskedAccount;

    /**
     * @var ?string $expDate
     */
    #[JsonProperty('ExpDate')]
    public ?string $expDate;

    /**
     * @var ?string $holderName
     */
    #[JsonProperty('HolderName')]
    public ?string $holderName;

    /**
     * @var ?string $achSecCode
     */
    #[JsonProperty('AchSecCode')]
    public ?string $achSecCode;

    /**
     * @var ?string $achHolderType
     */
    #[JsonProperty('AchHolderType')]
    public ?string $achHolderType;

    /**
     * @var ?bool $isValidatedAch
     */
    #[JsonProperty('IsValidatedACH')]
    public ?bool $isValidatedAch;

    /**
     * @var ?string $bin
     */
    #[JsonProperty('BIN')]
    public ?string $bin;

    /**
     * @var ?string $binData
     */
    #[JsonProperty('binData')]
    public ?string $binData;

    /**
     * @var ?string $aba
     */
    #[JsonProperty('ABA')]
    public ?string $aba;

    /**
     * @var ?string $postalCode
     */
    #[JsonProperty('PostalCode')]
    public ?string $postalCode;

    /**
     * @var ?string $methodType
     */
    #[JsonProperty('MethodType')]
    public ?string $methodType;

    /**
     * @var ?DateTime $lastUpdated
     */
    #[JsonProperty('LastUpdated'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $lastUpdated;

    /**
     * @var ?DateTime $cardUpdatedOn
     */
    #[JsonProperty('CardUpdatedOn'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $cardUpdatedOn;

    /**
     * @param array{
     *   idPmethod?: ?string,
     *   method?: ?string,
     *   descriptor?: ?string,
     *   maskedAccount?: ?string,
     *   expDate?: ?string,
     *   holderName?: ?string,
     *   achSecCode?: ?string,
     *   achHolderType?: ?string,
     *   isValidatedAch?: ?bool,
     *   bin?: ?string,
     *   binData?: ?string,
     *   aba?: ?string,
     *   postalCode?: ?string,
     *   methodType?: ?string,
     *   lastUpdated?: ?DateTime,
     *   cardUpdatedOn?: ?DateTime,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->idPmethod = $values['idPmethod'] ?? null;
        $this->method = $values['method'] ?? null;
        $this->descriptor = $values['descriptor'] ?? null;
        $this->maskedAccount = $values['maskedAccount'] ?? null;
        $this->expDate = $values['expDate'] ?? null;
        $this->holderName = $values['holderName'] ?? null;
        $this->achSecCode = $values['achSecCode'] ?? null;
        $this->achHolderType = $values['achHolderType'] ?? null;
        $this->isValidatedAch = $values['isValidatedAch'] ?? null;
        $this->bin = $values['bin'] ?? null;
        $this->binData = $values['binData'] ?? null;
        $this->aba = $values['aba'] ?? null;
        $this->postalCode = $values['postalCode'] ?? null;
        $this->methodType = $values['methodType'] ?? null;
        $this->lastUpdated = $values['lastUpdated'] ?? null;
        $this->cardUpdatedOn = $values['cardUpdatedOn'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
