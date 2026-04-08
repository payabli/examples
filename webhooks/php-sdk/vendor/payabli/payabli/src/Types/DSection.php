<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class DSection extends JsonSerializableType
{
    /**
     * @var ?Bnk $depositAccount
     */
    #[JsonProperty('depositAccount')]
    public ?Bnk $depositAccount;

    /**
     * @var ?Bnk $withdrawalAccount
     */
    #[JsonProperty('withdrawalAccount')]
    public ?Bnk $withdrawalAccount;

    /**
     * @param array{
     *   depositAccount?: ?Bnk,
     *   withdrawalAccount?: ?Bnk,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->depositAccount = $values['depositAccount'] ?? null;
        $this->withdrawalAccount = $values['withdrawalAccount'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
