<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class OrgData extends JsonSerializableType
{
    /**
     * @var ?int $idOrg
     */
    #[JsonProperty('idOrg')]
    public ?int $idOrg;

    /**
     * @var ?string $orgAddress
     */
    #[JsonProperty('orgAddress')]
    public ?string $orgAddress;

    /**
     * @var ?FileContent $orgLogo
     */
    #[JsonProperty('orgLogo')]
    public ?FileContent $orgLogo;

    /**
     * @var ?string $orgName
     */
    #[JsonProperty('orgName')]
    public ?string $orgName;

    /**
     * The paypoint's status.
     *
     * Active - `1`
     *
     * Inactive - 0
     *
     * @var ?int $orgStatus
     */
    #[JsonProperty('orgStatus')]
    public ?int $orgStatus;

    /**
     * @var ?int $orgType
     */
    #[JsonProperty('orgType')]
    public ?int $orgType;

    /**
     * @param array{
     *   idOrg?: ?int,
     *   orgAddress?: ?string,
     *   orgLogo?: ?FileContent,
     *   orgName?: ?string,
     *   orgStatus?: ?int,
     *   orgType?: ?int,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->idOrg = $values['idOrg'] ?? null;
        $this->orgAddress = $values['orgAddress'] ?? null;
        $this->orgLogo = $values['orgLogo'] ?? null;
        $this->orgName = $values['orgName'] ?? null;
        $this->orgStatus = $values['orgStatus'] ?? null;
        $this->orgType = $values['orgType'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
