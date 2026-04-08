<?php

namespace Payabli\User\Requests;

use Payabli\Core\Json\JsonSerializableType;

class GetUserRequest extends JsonSerializableType
{
    /**
     * @var ?string $entry The entrypoint identifier.
     */
    public ?string $entry;

    /**
     * @var ?int $level Entry level: 0 - partner, 2 - paypoint
     */
    public ?int $level;

    /**
     * @param array{
     *   entry?: ?string,
     *   level?: ?int,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->entry = $values['entry'] ?? null;
        $this->level = $values['level'] ?? null;
    }
}
