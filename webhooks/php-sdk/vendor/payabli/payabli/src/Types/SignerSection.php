<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class SignerSection extends JsonSerializableType
{
    /**
     * @var ?bool $visible
     */
    #[JsonProperty('visible')]
    public ?bool $visible;

    /**
     * @var ?TemplateElement $name
     */
    #[JsonProperty('name')]
    public ?TemplateElement $name;

    /**
     * @var ?TemplateElement $ssn
     */
    #[JsonProperty('ssn')]
    public ?TemplateElement $ssn;

    /**
     * @var ?TemplateElement $dob
     */
    #[JsonProperty('dob')]
    public ?TemplateElement $dob;

    /**
     * @var ?TemplateElement $phone
     */
    #[JsonProperty('phone')]
    public ?TemplateElement $phone;

    /**
     * @var ?TemplateElement $email
     */
    #[JsonProperty('email')]
    public ?TemplateElement $email;

    /**
     * @var ?TemplateElement $address
     */
    #[JsonProperty('address')]
    public ?TemplateElement $address;

    /**
     * @var ?TemplateElement $address1
     */
    #[JsonProperty('address1')]
    public ?TemplateElement $address1;

    /**
     * @var ?TemplateElement $city
     */
    #[JsonProperty('city')]
    public ?TemplateElement $city;

    /**
     * @var ?TemplateElement $country
     */
    #[JsonProperty('country')]
    public ?TemplateElement $country;

    /**
     * @var ?TemplateElement $state
     */
    #[JsonProperty('state')]
    public ?TemplateElement $state;

    /**
     * @var ?TemplateElement $zip
     */
    #[JsonProperty('zip')]
    public ?TemplateElement $zip;

    /**
     * @var ?TemplateElement $acceptance
     */
    #[JsonProperty('acceptance')]
    public ?TemplateElement $acceptance;

    /**
     * @var ?TemplateElement $signedDocumentReference
     */
    #[JsonProperty('signedDocumentReference')]
    public ?TemplateElement $signedDocumentReference;

    /**
     * @var ?TemplateAdditionalDataSection $additionalData
     */
    #[JsonProperty('additionalData')]
    public ?TemplateAdditionalDataSection $additionalData;

    /**
     * @param array{
     *   visible?: ?bool,
     *   name?: ?TemplateElement,
     *   ssn?: ?TemplateElement,
     *   dob?: ?TemplateElement,
     *   phone?: ?TemplateElement,
     *   email?: ?TemplateElement,
     *   address?: ?TemplateElement,
     *   address1?: ?TemplateElement,
     *   city?: ?TemplateElement,
     *   country?: ?TemplateElement,
     *   state?: ?TemplateElement,
     *   zip?: ?TemplateElement,
     *   acceptance?: ?TemplateElement,
     *   signedDocumentReference?: ?TemplateElement,
     *   additionalData?: ?TemplateAdditionalDataSection,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->visible = $values['visible'] ?? null;
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
        $this->additionalData = $values['additionalData'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
