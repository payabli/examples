<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

/**
 * Information about the application's signer.
 */
class SignerDataRequest extends JsonSerializableType
{
    /**
     * @var ?string $name
     */
    #[JsonProperty('name')]
    public ?string $name;

    /**
     * @var ?string $ssn
     */
    #[JsonProperty('ssn')]
    public ?string $ssn;

    /**
     * @var ?string $dob
     */
    #[JsonProperty('dob')]
    public ?string $dob;

    /**
     * @var ?string $phone
     */
    #[JsonProperty('phone')]
    public ?string $phone;

    /**
     * @var ?string $email The signer's email address.
     */
    #[JsonProperty('email')]
    public ?string $email;

    /**
     * @var ?string $address
     */
    #[JsonProperty('address')]
    public ?string $address;

    /**
     * @var ?string $address1
     */
    #[JsonProperty('address1')]
    public ?string $address1;

    /**
     * @var ?string $city
     */
    #[JsonProperty('city')]
    public ?string $city;

    /**
     * @var ?string $country
     */
    #[JsonProperty('country')]
    public ?string $country;

    /**
     * @var ?string $state
     */
    #[JsonProperty('state')]
    public ?string $state;

    /**
     * @var ?string $zip
     */
    #[JsonProperty('zip')]
    public ?string $zip;

    /**
     * @var ?bool $acceptance
     */
    #[JsonProperty('acceptance')]
    public ?bool $acceptance;

    /**
     * @var ?string $signedDocumentReference
     */
    #[JsonProperty('signedDocumentReference')]
    public ?string $signedDocumentReference;

    /**
     * @var ?bool $pciAttestation
     */
    #[JsonProperty('pciAttestation')]
    public ?bool $pciAttestation;

    /**
     * @var ?string $attestationDate
     */
    #[JsonProperty('attestationDate')]
    public ?string $attestationDate;

    /**
     * @var ?string $additionalData
     */
    #[JsonProperty('additionalData')]
    public ?string $additionalData;

    /**
     * @var ?string $signDate
     */
    #[JsonProperty('signDate')]
    public ?string $signDate;

    /**
     * @param array{
     *   name?: ?string,
     *   ssn?: ?string,
     *   dob?: ?string,
     *   phone?: ?string,
     *   email?: ?string,
     *   address?: ?string,
     *   address1?: ?string,
     *   city?: ?string,
     *   country?: ?string,
     *   state?: ?string,
     *   zip?: ?string,
     *   acceptance?: ?bool,
     *   signedDocumentReference?: ?string,
     *   pciAttestation?: ?bool,
     *   attestationDate?: ?string,
     *   additionalData?: ?string,
     *   signDate?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->name = $values['name'] ?? null;
        $this->ssn = $values['ssn'] ?? null;
        $this->dob = $values['dob'] ?? null;
        $this->phone = $values['phone'] ?? null;
        $this->email = $values['email'] ?? null;
        $this->address = $values['address'] ?? null;
        $this->address1 = $values['address1'] ?? null;
        $this->city = $values['city'] ?? null;
        $this->country = $values['country'] ?? null;
        $this->state = $values['state'] ?? null;
        $this->zip = $values['zip'] ?? null;
        $this->acceptance = $values['acceptance'] ?? null;
        $this->signedDocumentReference = $values['signedDocumentReference'] ?? null;
        $this->pciAttestation = $values['pciAttestation'] ?? null;
        $this->attestationDate = $values['attestationDate'] ?? null;
        $this->additionalData = $values['additionalData'] ?? null;
        $this->signDate = $values['signDate'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
