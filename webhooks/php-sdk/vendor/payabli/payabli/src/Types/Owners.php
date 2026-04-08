<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class Owners extends JsonSerializableType
{
    /**
     * @var ?string $ownername Person who is registered as the beneficial owner of the business. This is a combination of first and last name.
     */
    #[JsonProperty('ownername')]
    public ?string $ownername;

    /**
     * @var ?string $ownertitle The job title of the person such as CEO or director.
     */
    #[JsonProperty('ownertitle')]
    public ?string $ownertitle;

    /**
     * @var ?int $ownerpercent Percentage of ownership the person holds, in integer format.
     */
    #[JsonProperty('ownerpercent')]
    public ?int $ownerpercent;

    /**
     * @var ?string $ownerssn The relevant identifier for the person such as a Social Security Number.
     */
    #[JsonProperty('ownerssn')]
    public ?string $ownerssn;

    /**
     * @var ?string $ownerdob Owner's date of birth.
     */
    #[JsonProperty('ownerdob')]
    public ?string $ownerdob;

    /**
     * @var ?string $ownerphone1 Owner phone 1.
     */
    #[JsonProperty('ownerphone1')]
    public ?string $ownerphone1;

    /**
     * @var ?string $ownerphone2 Owner phone 2.
     */
    #[JsonProperty('ownerphone2')]
    public ?string $ownerphone2;

    /**
     * @var ?string $owneremail Owner email.
     */
    #[JsonProperty('owneremail')]
    public ?string $owneremail;

    /**
     * @var ?string $ownerdriver Owner driver's license ID number. Payabli strongly recommends including this.
     */
    #[JsonProperty('ownerdriver')]
    public ?string $ownerdriver;

    /**
     * @var ?string $oaddress Owner street address. This must be the physical address of the owner, not a P.O. box.
     */
    #[JsonProperty('oaddress')]
    public ?string $oaddress;

    /**
     * @var ?string $ocity Owner address city.
     */
    #[JsonProperty('ocity')]
    public ?string $ocity;

    /**
     * @var ?string $ocountry Owner address country in ISO-3166-1 alpha 2 format. Check out https://en.wikipedia.org/wiki/ISO_3166-1 for reference.
     */
    #[JsonProperty('ocountry')]
    public ?string $ocountry;

    /**
     * @var ?string $odriverstate Owner driver's license State. Payabli strongly recommends including this.
     */
    #[JsonProperty('odriverstate')]
    public ?string $odriverstate;

    /**
     * @var ?string $ostate Owner address state.
     */
    #[JsonProperty('ostate')]
    public ?string $ostate;

    /**
     * @var ?string $ozip Owner address ZIP.
     */
    #[JsonProperty('ozip')]
    public ?string $ozip;

    /**
     * @var ?string $additionalData
     */
    #[JsonProperty('additionalData')]
    public ?string $additionalData;

    /**
     * @param array{
     *   ownername?: ?string,
     *   ownertitle?: ?string,
     *   ownerpercent?: ?int,
     *   ownerssn?: ?string,
     *   ownerdob?: ?string,
     *   ownerphone1?: ?string,
     *   ownerphone2?: ?string,
     *   owneremail?: ?string,
     *   ownerdriver?: ?string,
     *   oaddress?: ?string,
     *   ocity?: ?string,
     *   ocountry?: ?string,
     *   odriverstate?: ?string,
     *   ostate?: ?string,
     *   ozip?: ?string,
     *   additionalData?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->ownername = $values['ownername'] ?? null;
        $this->ownertitle = $values['ownertitle'] ?? null;
        $this->ownerpercent = $values['ownerpercent'] ?? null;
        $this->ownerssn = $values['ownerssn'] ?? null;
        $this->ownerdob = $values['ownerdob'] ?? null;
        $this->ownerphone1 = $values['ownerphone1'] ?? null;
        $this->ownerphone2 = $values['ownerphone2'] ?? null;
        $this->owneremail = $values['owneremail'] ?? null;
        $this->ownerdriver = $values['ownerdriver'] ?? null;
        $this->oaddress = $values['oaddress'] ?? null;
        $this->ocity = $values['ocity'] ?? null;
        $this->ocountry = $values['ocountry'] ?? null;
        $this->odriverstate = $values['odriverstate'] ?? null;
        $this->ostate = $values['ostate'] ?? null;
        $this->ozip = $values['ozip'] ?? null;
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
