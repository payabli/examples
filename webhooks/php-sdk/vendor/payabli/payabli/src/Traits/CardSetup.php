<?php

namespace Payabli\Traits;

use Payabli\Core\Json\JsonProperty;

/**
 * @property ?bool $acceptAmex
 * @property ?bool $acceptDiscover
 * @property ?bool $acceptMastercard
 * @property ?bool $acceptVisa
 */
trait CardSetup
{
    /**
     * @var ?bool $acceptAmex Determines whether American Express is accepted.
     */
    #[JsonProperty('acceptAmex')]
    public ?bool $acceptAmex;

    /**
     * @var ?bool $acceptDiscover Determines whether Discover is accepted.
     */
    #[JsonProperty('acceptDiscover')]
    public ?bool $acceptDiscover;

    /**
     * @var ?bool $acceptMastercard Determines whether Mastercard is accepted.
     */
    #[JsonProperty('acceptMastercard')]
    public ?bool $acceptMastercard;

    /**
     * @var ?bool $acceptVisa Determines whether Visa is accepted.
     */
    #[JsonProperty('acceptVisa')]
    public ?bool $acceptVisa;
}
