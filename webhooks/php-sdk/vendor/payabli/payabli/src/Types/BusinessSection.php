<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

/**
 * Details about a business.
 */
class BusinessSection extends JsonSerializableType
{
    /**
     * @var ?TemplateElement $baddress
     */
    #[JsonProperty('baddress')]
    public ?TemplateElement $baddress;

    /**
     * @var ?TemplateElement $baddress1
     */
    #[JsonProperty('baddress1')]
    public ?TemplateElement $baddress1;

    /**
     * @var ?TemplateElement $bcity
     */
    #[JsonProperty('bcity')]
    public ?TemplateElement $bcity;

    /**
     * @var ?TemplateElement $bcountry
     */
    #[JsonProperty('bcountry')]
    public ?TemplateElement $bcountry;

    /**
     * @var ?TemplateElement $bstate
     */
    #[JsonProperty('bstate')]
    public ?TemplateElement $bstate;

    /**
     * @var ?TemplateElement $btype
     */
    #[JsonProperty('btype')]
    public ?TemplateElement $btype;

    /**
     * @var ?TemplateElement $bzip
     */
    #[JsonProperty('bzip')]
    public ?TemplateElement $bzip;

    /**
     * @var ?TemplateElement $dbaname
     */
    #[JsonProperty('dbaname')]
    public ?TemplateElement $dbaname;

    /**
     * @var ?TemplateElement $ein
     */
    #[JsonProperty('ein')]
    public ?TemplateElement $ein;

    /**
     * @var ?TemplateElement $faxnumber
     */
    #[JsonProperty('faxnumber')]
    public ?TemplateElement $faxnumber;

    /**
     * @var ?TemplateElement $legalname
     */
    #[JsonProperty('legalname')]
    public ?TemplateElement $legalname;

    /**
     * @var ?TemplateElement $license
     */
    #[JsonProperty('license')]
    public ?TemplateElement $license;

    /**
     * @var ?TemplateElement $licstate
     */
    #[JsonProperty('licstate')]
    public ?TemplateElement $licstate;

    /**
     * @var ?TemplateElement $maddress
     */
    #[JsonProperty('maddress')]
    public ?TemplateElement $maddress;

    /**
     * @var ?TemplateElement $maddress1
     */
    #[JsonProperty('maddress1')]
    public ?TemplateElement $maddress1;

    /**
     * @var ?TemplateElement $mcity
     */
    #[JsonProperty('mcity')]
    public ?TemplateElement $mcity;

    /**
     * @var ?TemplateElement $mcountry
     */
    #[JsonProperty('mcountry')]
    public ?TemplateElement $mcountry;

    /**
     * @var ?TemplateElement $mstate
     */
    #[JsonProperty('mstate')]
    public ?TemplateElement $mstate;

    /**
     * @var ?TemplateElement $mzip
     */
    #[JsonProperty('mzip')]
    public ?TemplateElement $mzip;

    /**
     * @var ?TemplateElement $phonenumber
     */
    #[JsonProperty('phonenumber')]
    public ?TemplateElement $phonenumber;

    /**
     * @var ?TemplateElement $startdate
     */
    #[JsonProperty('startdate')]
    public ?TemplateElement $startdate;

    /**
     * @var ?TemplateElement $taxfillname
     */
    #[JsonProperty('taxfillname')]
    public ?TemplateElement $taxfillname;

    /**
     * @var ?bool $visible
     */
    #[JsonProperty('visible')]
    public ?bool $visible;

    /**
     * @var ?TemplateElement $website
     */
    #[JsonProperty('website')]
    public ?TemplateElement $website;

    /**
     * @var ?TemplateAdditionalDataSection $additionalData
     */
    #[JsonProperty('additionalData')]
    public ?TemplateAdditionalDataSection $additionalData;

    /**
     * @param array{
     *   baddress?: ?TemplateElement,
     *   baddress1?: ?TemplateElement,
     *   bcity?: ?TemplateElement,
     *   bcountry?: ?TemplateElement,
     *   bstate?: ?TemplateElement,
     *   btype?: ?TemplateElement,
     *   bzip?: ?TemplateElement,
     *   dbaname?: ?TemplateElement,
     *   ein?: ?TemplateElement,
     *   faxnumber?: ?TemplateElement,
     *   legalname?: ?TemplateElement,
     *   license?: ?TemplateElement,
     *   licstate?: ?TemplateElement,
     *   maddress?: ?TemplateElement,
     *   maddress1?: ?TemplateElement,
     *   mcity?: ?TemplateElement,
     *   mcountry?: ?TemplateElement,
     *   mstate?: ?TemplateElement,
     *   mzip?: ?TemplateElement,
     *   phonenumber?: ?TemplateElement,
     *   startdate?: ?TemplateElement,
     *   taxfillname?: ?TemplateElement,
     *   visible?: ?bool,
     *   website?: ?TemplateElement,
     *   additionalData?: ?TemplateAdditionalDataSection,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->baddress = $values['baddress'] ?? null;
        $this->baddress1 = $values['baddress1'] ?? null;
        $this->bcity = $values['bcity'] ?? null;
        $this->bcountry = $values['bcountry'] ?? null;
        $this->bstate = $values['bstate'] ?? null;
        $this->btype = $values['btype'] ?? null;
        $this->bzip = $values['bzip'] ?? null;
        $this->dbaname = $values['dbaname'] ?? null;
        $this->ein = $values['ein'] ?? null;
        $this->faxnumber = $values['faxnumber'] ?? null;
        $this->legalname = $values['legalname'] ?? null;
        $this->license = $values['license'] ?? null;
        $this->licstate = $values['licstate'] ?? null;
        $this->maddress = $values['maddress'] ?? null;
        $this->maddress1 = $values['maddress1'] ?? null;
        $this->mcity = $values['mcity'] ?? null;
        $this->mcountry = $values['mcountry'] ?? null;
        $this->mstate = $values['mstate'] ?? null;
        $this->mzip = $values['mzip'] ?? null;
        $this->phonenumber = $values['phonenumber'] ?? null;
        $this->startdate = $values['startdate'] ?? null;
        $this->taxfillname = $values['taxfillname'] ?? null;
        $this->visible = $values['visible'] ?? null;
        $this->website = $values['website'] ?? null;
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
