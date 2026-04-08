<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class OList extends JsonSerializableType
{
    /**
     * @var ?LinkData $oaddress
     */
    #[JsonProperty('oaddress')]
    public ?LinkData $oaddress;

    /**
     * @var ?LinkData $ocity
     */
    #[JsonProperty('ocity')]
    public ?LinkData $ocity;

    /**
     * @var ?LinkData $ocountry
     */
    #[JsonProperty('ocountry')]
    public ?LinkData $ocountry;

    /**
     * @var ?LinkData $odriverstate
     */
    #[JsonProperty('odriverstate')]
    public ?LinkData $odriverstate;

    /**
     * @var ?LinkData $ostate
     */
    #[JsonProperty('ostate')]
    public ?LinkData $ostate;

    /**
     * @var ?LinkData $ownerdob
     */
    #[JsonProperty('ownerdob')]
    public ?LinkData $ownerdob;

    /**
     * @var ?LinkData $ownerdriver
     */
    #[JsonProperty('ownerdriver')]
    public ?LinkData $ownerdriver;

    /**
     * @var ?LinkData $owneremail
     */
    #[JsonProperty('owneremail')]
    public ?LinkData $owneremail;

    /**
     * @var ?LinkData $ownername
     */
    #[JsonProperty('ownername')]
    public ?LinkData $ownername;

    /**
     * @var ?LinkData $ownerpercent
     */
    #[JsonProperty('ownerpercent')]
    public ?LinkData $ownerpercent;

    /**
     * @var ?LinkData $ownerphone1
     */
    #[JsonProperty('ownerphone1')]
    public ?LinkData $ownerphone1;

    /**
     * @var ?LinkData $ownerphone2
     */
    #[JsonProperty('ownerphone2')]
    public ?LinkData $ownerphone2;

    /**
     * @var ?LinkData $ownerssn
     */
    #[JsonProperty('ownerssn')]
    public ?LinkData $ownerssn;

    /**
     * @var ?LinkData $ownertitle
     */
    #[JsonProperty('ownertitle')]
    public ?LinkData $ownertitle;

    /**
     * @var ?LinkData $ozip
     */
    #[JsonProperty('ozip')]
    public ?LinkData $ozip;

    /**
     * @param array{
     *   oaddress?: ?LinkData,
     *   ocity?: ?LinkData,
     *   ocountry?: ?LinkData,
     *   odriverstate?: ?LinkData,
     *   ostate?: ?LinkData,
     *   ownerdob?: ?LinkData,
     *   ownerdriver?: ?LinkData,
     *   owneremail?: ?LinkData,
     *   ownername?: ?LinkData,
     *   ownerpercent?: ?LinkData,
     *   ownerphone1?: ?LinkData,
     *   ownerphone2?: ?LinkData,
     *   ownerssn?: ?LinkData,
     *   ownertitle?: ?LinkData,
     *   ozip?: ?LinkData,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
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
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
