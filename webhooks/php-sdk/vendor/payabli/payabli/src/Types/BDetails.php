<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class BDetails extends JsonSerializableType
{
    /**
     * @var ?LinkData $btype
     */
    #[JsonProperty('btype')]
    public ?LinkData $btype;

    /**
     * @var ?LinkData $dbaname
     */
    #[JsonProperty('dbaname')]
    public ?LinkData $dbaname;

    /**
     * @var ?LinkData $ein
     */
    #[JsonProperty('ein')]
    public ?LinkData $ein;

    /**
     * @var ?LinkData $faxnumber
     */
    #[JsonProperty('faxnumber')]
    public ?LinkData $faxnumber;

    /**
     * @var ?LinkData $legalname
     */
    #[JsonProperty('legalname')]
    public ?LinkData $legalname;

    /**
     * @var ?LinkData $license
     */
    #[JsonProperty('license')]
    public ?LinkData $license;

    /**
     * @var ?LinkData $licstate
     */
    #[JsonProperty('licstate')]
    public ?LinkData $licstate;

    /**
     * @var ?LinkData $phonenumber
     */
    #[JsonProperty('phonenumber')]
    public ?LinkData $phonenumber;

    /**
     * @var ?LinkData $startdate
     */
    #[JsonProperty('startdate')]
    public ?LinkData $startdate;

    /**
     * @var ?LinkData $taxfillname
     */
    #[JsonProperty('taxfillname')]
    public ?LinkData $taxfillname;

    /**
     * @var ?LinkData $website
     */
    #[JsonProperty('website')]
    public ?LinkData $website;

    /**
     * @param array{
     *   btype?: ?LinkData,
     *   dbaname?: ?LinkData,
     *   ein?: ?LinkData,
     *   faxnumber?: ?LinkData,
     *   legalname?: ?LinkData,
     *   license?: ?LinkData,
     *   licstate?: ?LinkData,
     *   phonenumber?: ?LinkData,
     *   startdate?: ?LinkData,
     *   taxfillname?: ?LinkData,
     *   website?: ?LinkData,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->btype = $values['btype'] ?? null;
        $this->dbaname = $values['dbaname'] ?? null;
        $this->ein = $values['ein'] ?? null;
        $this->faxnumber = $values['faxnumber'] ?? null;
        $this->legalname = $values['legalname'] ?? null;
        $this->license = $values['license'] ?? null;
        $this->licstate = $values['licstate'] ?? null;
        $this->phonenumber = $values['phonenumber'] ?? null;
        $this->startdate = $values['startdate'] ?? null;
        $this->taxfillname = $values['taxfillname'] ?? null;
        $this->website = $values['website'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
