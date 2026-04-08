<?php

namespace Payabli\Traits;

use Payabli\Core\Json\JsonProperty;

/**
 * @property ?string $ownername
 * @property ?string $ownertitle
 * @property ?int $ownerpercent
 * @property ?string $ownerssn
 * @property ?string $ownerdob
 * @property ?string $ownerphone1
 * @property ?string $ownerphone2
 * @property ?string $owneremail
 * @property ?string $ownerdriver
 * @property ?string $oaddress
 * @property ?string $ocity
 * @property ?string $ocountry
 * @property ?string $odriverstate
 * @property ?string $ostate
 * @property ?string $ozip
 * @property ?string $additionalData
 */
trait Owners
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
}
