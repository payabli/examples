<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use DateTime;
use Payabli\Core\Types\Date;

class MethodQueryRecords extends JsonSerializableType
{
    /**
     * @var ?string $bin The bank identification number (BIN). Null when method is ACH.
     */
    #[JsonProperty('bin')]
    public ?string $bin;

    /**
     * @var ?BinData $binData
     */
    #[JsonProperty('binData')]
    public ?BinData $binData;

    /**
     * @var ?string $descriptor
     */
    #[JsonProperty('descriptor')]
    public ?string $descriptor;

    /**
     * @var ?string $expDate Expiration date associated to the method (only for card) in format MMYY.
     */
    #[JsonProperty('expDate')]
    public ?string $expDate;

    /**
     * @var ?string $holderName
     */
    #[JsonProperty('holderName')]
    public ?string $holderName;

    /**
     * @var ?string $idPmethod Method internal ID
     */
    #[JsonProperty('idPmethod')]
    public ?string $idPmethod;

    /**
     * @var ?DateTime $lastUpdated Date of last update
     */
    #[JsonProperty('lastUpdated'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $lastUpdated;

    /**
     * @var ?string $maskedAccount
     */
    #[JsonProperty('maskedAccount')]
    public ?string $maskedAccount;

    /**
     * @var ?string $method Type of payment vehicle: **ach** or **card**
     */
    #[JsonProperty('method')]
    public ?string $method;

    /**
     * @param array{
     *   bin?: ?string,
     *   binData?: ?BinData,
     *   descriptor?: ?string,
     *   expDate?: ?string,
     *   holderName?: ?string,
     *   idPmethod?: ?string,
     *   lastUpdated?: ?DateTime,
     *   maskedAccount?: ?string,
     *   method?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->bin = $values['bin'] ?? null;
        $this->binData = $values['binData'] ?? null;
        $this->descriptor = $values['descriptor'] ?? null;
        $this->expDate = $values['expDate'] ?? null;
        $this->holderName = $values['holderName'] ?? null;
        $this->idPmethod = $values['idPmethod'] ?? null;
        $this->lastUpdated = $values['lastUpdated'] ?? null;
        $this->maskedAccount = $values['maskedAccount'] ?? null;
        $this->method = $values['method'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
