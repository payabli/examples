<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

/**
 * Information about a business owner.
 */
class OwnersSection extends JsonSerializableType
{
    /**
     * @var ?TemplateElement $contactEmail
     */
    #[JsonProperty('contactEmail')]
    public ?TemplateElement $contactEmail;

    /**
     * @var ?TemplateElement $contactName
     */
    #[JsonProperty('contactName')]
    public ?TemplateElement $contactName;

    /**
     * @var ?TemplateElement $contactPhone
     */
    #[JsonProperty('contactPhone')]
    public ?TemplateElement $contactPhone;

    /**
     * @var ?TemplateElement $contactTitle
     */
    #[JsonProperty('contactTitle')]
    public ?TemplateElement $contactTitle;

    /**
     * @var ?bool $multipleContacts Offer add more contacts
     */
    #[JsonProperty('multipleContacts')]
    public ?bool $multipleContacts;

    /**
     * @var ?bool $multipleOwners offer add more owners
     */
    #[JsonProperty('multipleOwners')]
    public ?bool $multipleOwners;

    /**
     * @var ?TemplateElement $oaddress
     */
    #[JsonProperty('oaddress')]
    public ?TemplateElement $oaddress;

    /**
     * @var ?TemplateElement $ocity
     */
    #[JsonProperty('ocity')]
    public ?TemplateElement $ocity;

    /**
     * @var ?TemplateElement $ocountry
     */
    #[JsonProperty('ocountry')]
    public ?TemplateElement $ocountry;

    /**
     * @var ?TemplateElement $odriverstate
     */
    #[JsonProperty('odriverstate')]
    public ?TemplateElement $odriverstate;

    /**
     * @var ?TemplateElement $ostate
     */
    #[JsonProperty('ostate')]
    public ?TemplateElement $ostate;

    /**
     * @var ?TemplateElement $ownerdob
     */
    #[JsonProperty('ownerdob')]
    public ?TemplateElement $ownerdob;

    /**
     * @var ?TemplateElement $ownerdriver
     */
    #[JsonProperty('ownerdriver')]
    public ?TemplateElement $ownerdriver;

    /**
     * @var ?TemplateElement $owneremail
     */
    #[JsonProperty('owneremail')]
    public ?TemplateElement $owneremail;

    /**
     * @var ?TemplateElement $ownername
     */
    #[JsonProperty('ownername')]
    public ?TemplateElement $ownername;

    /**
     * @var ?TemplateElement $ownerpercent
     */
    #[JsonProperty('ownerpercent')]
    public ?TemplateElement $ownerpercent;

    /**
     * @var ?TemplateElement $ownerphone1
     */
    #[JsonProperty('ownerphone1')]
    public ?TemplateElement $ownerphone1;

    /**
     * @var ?TemplateElement $ownerphone2
     */
    #[JsonProperty('ownerphone2')]
    public ?TemplateElement $ownerphone2;

    /**
     * @var ?TemplateElement $ownerssn
     */
    #[JsonProperty('ownerssn')]
    public ?TemplateElement $ownerssn;

    /**
     * @var ?TemplateElement $ownertitle
     */
    #[JsonProperty('ownertitle')]
    public ?TemplateElement $ownertitle;

    /**
     * @var ?TemplateElement $ozip
     */
    #[JsonProperty('ozip')]
    public ?TemplateElement $ozip;

    /**
     * @var ?string $subFooter
     */
    #[JsonProperty('subFooter')]
    public ?string $subFooter;

    /**
     * @var ?string $subHeader
     */
    #[JsonProperty('subHeader')]
    public ?string $subHeader;

    /**
     * @var ?bool $visible
     */
    #[JsonProperty('visible')]
    public ?bool $visible;

    /**
     * @var ?TemplateAdditionalDataSection $additionalData
     */
    #[JsonProperty('additionalData')]
    public ?TemplateAdditionalDataSection $additionalData;

    /**
     * @param array{
     *   contactEmail?: ?TemplateElement,
     *   contactName?: ?TemplateElement,
     *   contactPhone?: ?TemplateElement,
     *   contactTitle?: ?TemplateElement,
     *   multipleContacts?: ?bool,
     *   multipleOwners?: ?bool,
     *   oaddress?: ?TemplateElement,
     *   ocity?: ?TemplateElement,
     *   ocountry?: ?TemplateElement,
     *   odriverstate?: ?TemplateElement,
     *   ostate?: ?TemplateElement,
     *   ownerdob?: ?TemplateElement,
     *   ownerdriver?: ?TemplateElement,
     *   owneremail?: ?TemplateElement,
     *   ownername?: ?TemplateElement,
     *   ownerpercent?: ?TemplateElement,
     *   ownerphone1?: ?TemplateElement,
     *   ownerphone2?: ?TemplateElement,
     *   ownerssn?: ?TemplateElement,
     *   ownertitle?: ?TemplateElement,
     *   ozip?: ?TemplateElement,
     *   subFooter?: ?string,
     *   subHeader?: ?string,
     *   visible?: ?bool,
     *   additionalData?: ?TemplateAdditionalDataSection,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->contactEmail = $values['contactEmail'] ?? null;
        $this->contactName = $values['contactName'] ?? null;
        $this->contactPhone = $values['contactPhone'] ?? null;
        $this->contactTitle = $values['contactTitle'] ?? null;
        $this->multipleContacts = $values['multipleContacts'] ?? null;
        $this->multipleOwners = $values['multipleOwners'] ?? null;
        $this->oaddress = $values['oaddress'] ?? null;
        $this->ocity = $values['ocity'] ?? null;
        $this->ocountry = $values['ocountry'] ?? null;
        $this->odriverstate = $values['odriverstate'] ?? null;
        $this->ostate = $values['ostate'] ?? null;
        $this->ownerdob = $values['ownerdob'] ?? null;
        $this->ownerdriver = $values['ownerdriver'] ?? null;
        $this->owneremail = $values['owneremail'] ?? null;
        $this->ownername = $values['ownername'] ?? null;
        $this->ownerpercent = $values['ownerpercent'] ?? null;
        $this->ownerphone1 = $values['ownerphone1'] ?? null;
        $this->ownerphone2 = $values['ownerphone2'] ?? null;
        $this->ownerssn = $values['ownerssn'] ?? null;
        $this->ownertitle = $values['ownertitle'] ?? null;
        $this->ozip = $values['ozip'] ?? null;
        $this->subFooter = $values['subFooter'] ?? null;
        $this->subHeader = $values['subHeader'] ?? null;
        $this->visible = $values['visible'] ?? null;
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
