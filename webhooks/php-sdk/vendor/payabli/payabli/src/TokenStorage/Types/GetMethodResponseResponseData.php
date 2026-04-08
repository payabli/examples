<?php

namespace Payabli\TokenStorage\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Types\AchHolderType;
use Payabli\Types\BinData;
use Payabli\Core\Types\ArrayType;
use DateTime;
use Payabli\Core\Types\Date;

class GetMethodResponseResponseData extends JsonSerializableType
{
    /**
     * @var ?string $aba Bank routing number
     */
    #[JsonProperty('aba')]
    public ?string $aba;

    /**
     * @var ?value-of<AchHolderType> $achHolderType
     */
    #[JsonProperty('achHolderType')]
    public ?string $achHolderType;

    /**
     * @var ?string $achSecCode
     */
    #[JsonProperty('achSecCode')]
    public ?string $achSecCode;

    /**
     * @var ?string $bin The bank identification number (BIN)
     */
    #[JsonProperty('bin')]
    public ?string $bin;

    /**
     * @var ?BinData $binData
     */
    #[JsonProperty('binData')]
    public ?BinData $binData;

    /**
     * @var ?array<GetMethodResponseResponseDataCustomersItem> $customers
     */
    #[JsonProperty('customers'), ArrayType([GetMethodResponseResponseDataCustomersItem::class])]
    public ?array $customers;

    /**
     * @var ?string $descriptor
     */
    #[JsonProperty('descriptor')]
    public ?string $descriptor;

    /**
     * @var ?string $expDate Expiration date for card in stored method in format MM/YY
     */
    #[JsonProperty('expDate')]
    public ?string $expDate;

    /**
     * @var ?string $holderName Account holder name in stored method
     */
    #[JsonProperty('holderName')]
    public ?string $holderName;

    /**
     * @var ?string $idPmethod The stored payment method's identifier in Payabli
     */
    #[JsonProperty('idPmethod')]
    public ?string $idPmethod;

    /**
     * @var ?DateTime $lastUpdated Timestamp for last update of stored method, in UTC
     */
    #[JsonProperty('lastUpdated'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $lastUpdated;

    /**
     * @var ?string $maskedAccount
     */
    #[JsonProperty('maskedAccount')]
    public ?string $maskedAccount;

    /**
     * @var ?string $method The saved method's type: `card` or `ach`.
     */
    #[JsonProperty('method')]
    public ?string $method;

    /**
     * @var ?string $methodType The payment method's token type
     */
    #[JsonProperty('methodType')]
    public ?string $methodType;

    /**
     * @var ?string $postalCode The payment method postal code
     */
    #[JsonProperty('postalCode')]
    public ?string $postalCode;

    /**
     * @param array{
     *   aba?: ?string,
     *   achHolderType?: ?value-of<AchHolderType>,
     *   achSecCode?: ?string,
     *   bin?: ?string,
     *   binData?: ?BinData,
     *   customers?: ?array<GetMethodResponseResponseDataCustomersItem>,
     *   descriptor?: ?string,
     *   expDate?: ?string,
     *   holderName?: ?string,
     *   idPmethod?: ?string,
     *   lastUpdated?: ?DateTime,
     *   maskedAccount?: ?string,
     *   method?: ?string,
     *   methodType?: ?string,
     *   postalCode?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->aba = $values['aba'] ?? null;
        $this->achHolderType = $values['achHolderType'] ?? null;
        $this->achSecCode = $values['achSecCode'] ?? null;
        $this->bin = $values['bin'] ?? null;
        $this->binData = $values['binData'] ?? null;
        $this->customers = $values['customers'] ?? null;
        $this->descriptor = $values['descriptor'] ?? null;
        $this->expDate = $values['expDate'] ?? null;
        $this->holderName = $values['holderName'] ?? null;
        $this->idPmethod = $values['idPmethod'] ?? null;
        $this->lastUpdated = $values['lastUpdated'] ?? null;
        $this->maskedAccount = $values['maskedAccount'] ?? null;
        $this->method = $values['method'] ?? null;
        $this->methodType = $values['methodType'] ?? null;
        $this->postalCode = $values['postalCode'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
