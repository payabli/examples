<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class BAddress extends JsonSerializableType
{
    /**
     * @var ?LinkData $baddress
     */
    #[JsonProperty('baddress')]
    public ?LinkData $baddress;

    /**
     * @var ?LinkData $baddress1
     */
    #[JsonProperty('baddress1')]
    public ?LinkData $baddress1;

    /**
     * @var ?LinkData $bcity
     */
    #[JsonProperty('bcity')]
    public ?LinkData $bcity;

    /**
     * @var ?LinkData $bcountry
     */
    #[JsonProperty('bcountry')]
    public ?LinkData $bcountry;

    /**
     * @var ?LinkData $bstate
     */
    #[JsonProperty('bstate')]
    public ?LinkData $bstate;

    /**
     * @var ?LinkData $bzip
     */
    #[JsonProperty('bzip')]
    public ?LinkData $bzip;

    /**
     * @var ?LinkData $maddress
     */
    #[JsonProperty('maddress')]
    public ?LinkData $maddress;

    /**
     * @var ?LinkData $maddress1
     */
    #[JsonProperty('maddress1')]
    public ?LinkData $maddress1;

    /**
     * @var ?LinkData $mcity
     */
    #[JsonProperty('mcity')]
    public ?LinkData $mcity;

    /**
     * @var ?LinkData $mcountry
     */
    #[JsonProperty('mcountry')]
    public ?LinkData $mcountry;

    /**
     * @var ?LinkData $mstate
     */
    #[JsonProperty('mstate')]
    public ?LinkData $mstate;

    /**
     * @var ?LinkData $mzip
     */
    #[JsonProperty('mzip')]
    public ?LinkData $mzip;

    /**
     * @param array{
     *   baddress?: ?LinkData,
     *   baddress1?: ?LinkData,
     *   bcity?: ?LinkData,
     *   bcountry?: ?LinkData,
     *   bstate?: ?LinkData,
     *   bzip?: ?LinkData,
     *   maddress?: ?LinkData,
     *   maddress1?: ?LinkData,
     *   mcity?: ?LinkData,
     *   mcountry?: ?LinkData,
     *   mstate?: ?LinkData,
     *   mzip?: ?LinkData,
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
        $this->bzip = $values['bzip'] ?? null;
        $this->maddress = $values['maddress'] ?? null;
        $this->maddress1 = $values['maddress1'] ?? null;
        $this->mcity = $values['mcity'] ?? null;
        $this->mcountry = $values['mcountry'] ?? null;
        $this->mstate = $values['mstate'] ?? null;
        $this->mzip = $values['mzip'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
