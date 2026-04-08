<?php

namespace Payabli\Bill\Requests;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Bill\Types\BillOutData;

class AddBillRequest extends JsonSerializableType
{
    /**
     * @var ?string $idempotencyKey
     */
    public ?string $idempotencyKey;

    /**
     * @var BillOutData $body
     */
    public BillOutData $body;

    /**
     * @param array{
     *   body: BillOutData,
     *   idempotencyKey?: ?string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->idempotencyKey = $values['idempotencyKey'] ?? null;
        $this->body = $values['body'];
    }
}
