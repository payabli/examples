<?php

namespace Payabli\User\Requests;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class UserAuthRequest extends JsonSerializableType
{
    /**
     * @var ?string $email
     */
    #[JsonProperty('email')]
    public ?string $email;

    /**
     * @var ?string $entry Identifier for entry point originating the request (used by front-end apps)
     */
    #[JsonProperty('entry')]
    public ?string $entry;

    /**
     * @var ?int $entryType Type of entry identifier: 0 - partner, 2 - paypoint. This is used by front-end apps, required if an Entry is indicated.
     */
    #[JsonProperty('entryType')]
    public ?int $entryType;

    /**
     * @var ?string $psw
     */
    #[JsonProperty('psw')]
    public ?string $psw;

    /**
     * @var ?int $userId
     */
    #[JsonProperty('userId')]
    public ?int $userId;

    /**
     * @var ?string $userTokenId
     */
    #[JsonProperty('userTokenId')]
    public ?string $userTokenId;

    /**
     * @param array{
     *   email?: ?string,
     *   entry?: ?string,
     *   entryType?: ?int,
     *   psw?: ?string,
     *   userId?: ?int,
     *   userTokenId?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->email = $values['email'] ?? null;
        $this->entry = $values['entry'] ?? null;
        $this->entryType = $values['entryType'] ?? null;
        $this->psw = $values['psw'] ?? null;
        $this->userId = $values['userId'] ?? null;
        $this->userTokenId = $values['userTokenId'] ?? null;
    }
}
