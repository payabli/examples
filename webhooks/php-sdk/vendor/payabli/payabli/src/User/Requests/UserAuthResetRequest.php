<?php

namespace Payabli\User\Requests;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class UserAuthResetRequest extends JsonSerializableType
{
    /**
     * @var ?string $email The user's email address.
     */
    #[JsonProperty('email')]
    public ?string $email;

    /**
     * @var ?string $entry Identifier for entrypoint originating the request (used by front-end apps)
     */
    #[JsonProperty('entry')]
    public ?string $entry;

    /**
     * @var ?int $entryType Type of entry identifier: 0 - partner, 2 - paypoint. This is used by front-end apps, required if an Entry is indicated.
     */
    #[JsonProperty('entryType')]
    public ?int $entryType;

    /**
     * @param array{
     *   email?: ?string,
     *   entry?: ?string,
     *   entryType?: ?int,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->email = $values['email'] ?? null;
        $this->entry = $values['entry'] ?? null;
        $this->entryType = $values['entryType'] ?? null;
    }
}
