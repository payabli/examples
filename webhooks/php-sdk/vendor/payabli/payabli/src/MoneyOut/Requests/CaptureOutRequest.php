<?php

namespace Payabli\MoneyOut\Requests;

use Payabli\Core\Json\JsonSerializableType;

class CaptureOutRequest extends JsonSerializableType
{
    /**
     * @var ?string $idempotencyKey
     */
    public ?string $idempotencyKey;

    /**
     * @param array{
     *   idempotencyKey?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->idempotencyKey = $values['idempotencyKey'] ?? null;
    }
}
