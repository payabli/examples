<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class CardSetup extends JsonSerializableType
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

    /**
     * @param array{
     *   acceptAmex?: ?bool,
     *   acceptDiscover?: ?bool,
     *   acceptMastercard?: ?bool,
     *   acceptVisa?: ?bool,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->acceptAmex = $values['acceptAmex'] ?? null;
        $this->acceptDiscover = $values['acceptDiscover'] ?? null;
        $this->acceptMastercard = $values['acceptMastercard'] ?? null;
        $this->acceptVisa = $values['acceptVisa'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
