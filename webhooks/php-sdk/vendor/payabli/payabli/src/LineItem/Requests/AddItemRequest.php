<?php

namespace Payabli\LineItem\Requests;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Types\LineItem;

class AddItemRequest extends JsonSerializableType
{
    /**
     * @var ?string $idempotencyKey A unique ID you can include to prevent duplicating objects or transactions if a request is sent more than once. This key isn't generated in Payabli, you must generate it yourself.
     */
    public ?string $idempotencyKey;

    /**
     * @var LineItem $body
     */
    public LineItem $body;

    /**
     * @param array{
     *   body: LineItem,
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
