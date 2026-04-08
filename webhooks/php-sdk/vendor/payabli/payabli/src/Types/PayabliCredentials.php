<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class PayabliCredentials extends JsonSerializableType
{
    /**
     * @var ?string $accountId
     */
    #[JsonProperty('accountId')]
    public ?string $accountId;

    /**
     * @var ?float $cfeeFix
     */
    #[JsonProperty('cfeeFix')]
    public ?float $cfeeFix;

    /**
     * @var ?float $cfeeFloat
     */
    #[JsonProperty('cfeeFloat')]
    public ?float $cfeeFloat;

    /**
     * @var ?float $cfeeMax
     */
    #[JsonProperty('cfeeMax')]
    public ?float $cfeeMax;

    /**
     * @var ?float $cfeeMin
     */
    #[JsonProperty('cfeeMin')]
    public ?float $cfeeMin;

    /**
     * @var ?float $maxticket
     */
    #[JsonProperty('maxticket')]
    public ?float $maxticket;

    /**
     * @var ?float $minticket
     */
    #[JsonProperty('minticket')]
    public ?float $minticket;

    /**
     * @var ?int $mode
     */
    #[JsonProperty('mode')]
    public ?int $mode;

    /**
     * @var ?int $referenceId
     */
    #[JsonProperty('referenceId')]
    public ?int $referenceId;

    /**
     * @var ?string $service
     */
    #[JsonProperty('service')]
    public ?string $service;

    /**
     * @param array{
     *   accountId?: ?string,
     *   cfeeFix?: ?float,
     *   cfeeFloat?: ?float,
     *   cfeeMax?: ?float,
     *   cfeeMin?: ?float,
     *   maxticket?: ?float,
     *   minticket?: ?float,
     *   mode?: ?int,
     *   referenceId?: ?int,
     *   service?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->accountId = $values['accountId'] ?? null;
        $this->cfeeFix = $values['cfeeFix'] ?? null;
        $this->cfeeFloat = $values['cfeeFloat'] ?? null;
        $this->cfeeMax = $values['cfeeMax'] ?? null;
        $this->cfeeMin = $values['cfeeMin'] ?? null;
        $this->maxticket = $values['maxticket'] ?? null;
        $this->minticket = $values['minticket'] ?? null;
        $this->mode = $values['mode'] ?? null;
        $this->referenceId = $values['referenceId'] ?? null;
        $this->service = $values['service'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
