<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

/**
 * Object containing information related to the card. This object is `null`
 * unless the payment method is card. If the payment method is Apple Pay, the
 * binData will be related to the DPAN (device primary account number), not
 * the card connected to Apple Pay.
 */
class BinData extends JsonSerializableType
{
    /**
     * The number of characters from the beginning of the card number that
     * were matched against a Bank Identification Number (BIN) or the Card
     * Range table.
     *
     * @var ?string $binMatchedLength
     */
    #[JsonProperty('binMatchedLength')]
    public ?string $binMatchedLength;

    /**
     * The card brand. For example, Visa, Mastercard, American Express,
     * Discover.
     *
     * @var ?string $binCardBrand
     */
    #[JsonProperty('binCardBrand')]
    public ?string $binCardBrand;

    /**
     * @var ?string $binCardType The type of card: Credit or Debit.
     */
    #[JsonProperty('binCardType')]
    public ?string $binCardType;

    /**
     * @var ?string $binCardCategory The category of the card, which indicates the card product. For example: Standard, Gold, Platinum, etc. The binCardCategory for prepaid cards is marked `PREPAID`.
     */
    #[JsonProperty('binCardCategory')]
    public ?string $binCardCategory;

    /**
     * @var ?string $binCardIssuer The name of the financial institution that issued the card.
     */
    #[JsonProperty('binCardIssuer')]
    public ?string $binCardIssuer;

    /**
     * @var ?string $binCardIssuerCountry The issuing financial institution's country name.
     */
    #[JsonProperty('binCardIssuerCountry')]
    public ?string $binCardIssuerCountry;

    /**
     * @var ?string $binCardIssuerCountryCodeA2 The issuing financial institution's two-character ISO country code. See [this resource](https://www.iso.org/obp/ui/#search) for a list of codes.
     */
    #[JsonProperty('binCardIssuerCountryCodeA2')]
    public ?string $binCardIssuerCountryCodeA2;

    /**
     * @var ?string $binCardIssuerCountryNumber The issuing financial institution's ISO standard numeric country code. See [this resource](https://www.iso.org/obp/ui/#search) for a list of codes.
     */
    #[JsonProperty('binCardIssuerCountryNumber')]
    public ?string $binCardIssuerCountryNumber;

    /**
     * @var ?string $binCardIsRegulated Indicates whether the card is regulated.
     */
    #[JsonProperty('binCardIsRegulated')]
    public ?string $binCardIsRegulated;

    /**
     * @var ?string $binCardUseCategory The use category classification for the card.
     */
    #[JsonProperty('binCardUseCategory')]
    public ?string $binCardUseCategory;

    /**
     * The issuing financial institution's three-character ISO country code.
     * See [this resource](https://www.iso.org/obp/ui/#search) for a list of
     * codes.
     *
     * @var ?string $binCardIssuerCountryCodeA3
     */
    #[JsonProperty('binCardIssuerCountryCodeA3')]
    public ?string $binCardIssuerCountryCodeA3;

    /**
     * @param array{
     *   binMatchedLength?: ?string,
     *   binCardBrand?: ?string,
     *   binCardType?: ?string,
     *   binCardCategory?: ?string,
     *   binCardIssuer?: ?string,
     *   binCardIssuerCountry?: ?string,
     *   binCardIssuerCountryCodeA2?: ?string,
     *   binCardIssuerCountryNumber?: ?string,
     *   binCardIsRegulated?: ?string,
     *   binCardUseCategory?: ?string,
     *   binCardIssuerCountryCodeA3?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->binMatchedLength = $values['binMatchedLength'] ?? null;
        $this->binCardBrand = $values['binCardBrand'] ?? null;
        $this->binCardType = $values['binCardType'] ?? null;
        $this->binCardCategory = $values['binCardCategory'] ?? null;
        $this->binCardIssuer = $values['binCardIssuer'] ?? null;
        $this->binCardIssuerCountry = $values['binCardIssuerCountry'] ?? null;
        $this->binCardIssuerCountryCodeA2 = $values['binCardIssuerCountryCodeA2'] ?? null;
        $this->binCardIssuerCountryNumber = $values['binCardIssuerCountryNumber'] ?? null;
        $this->binCardIsRegulated = $values['binCardIsRegulated'] ?? null;
        $this->binCardUseCategory = $values['binCardUseCategory'] ?? null;
        $this->binCardIssuerCountryCodeA3 = $values['binCardIssuerCountryCodeA3'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
