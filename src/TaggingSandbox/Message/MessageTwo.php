<?php

declare(strict_types=1);

namespace App\TaggingSandbox\Message;

use Mmalessa\MessengerAddonsBundle\ExternalMessageMapper\AsExternalMessage;

#[AsExternalMessage(schemaId: "some_sand.message_two", schemaFile: "dummy-schema.json")]
class MessageTwo
{

}