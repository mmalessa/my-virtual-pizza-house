<?php

declare(strict_types=1);

namespace App\ProcessManager\Infrastructure\Common\Repository\File;

use App\ProcessManager\Domain\MessageRepositoryInterface;
use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\Header;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\PaginationCursor;
use EventSauce\EventSourcing\Serialization\ConstructingMessageSerializer;
use EventSauce\EventSourcing\Serialization\ConstructingPayloadSerializer;
use EventSauce\EventSourcing\Serialization\MessageSerializer;
use Generator;

class MessageRepository implements MessageRepositoryInterface
{
    private MessageSerializer $messageSerializer;

    public function __construct(
        private readonly string $storageDirectory
    ) {
        $this->messageSerializer = new ConstructingMessageSerializer(
            null,
            new ConstructingPayloadSerializer()
        );
        if ( ! is_dir($this->storageDirectory)) {
            mkdir($this->storageDirectory);
        }
    }

    public function persist(Message ...$messages): void
    {
        foreach ($messages as $message) {
            $aggregateRootId = $message->header(Header::AGGREGATE_ROOT_ID)->toString();
            $version = $message->header(Header::AGGREGATE_ROOT_VERSION);
            $payload = $this->messageSerializer->serializeMessage($message);

            $directory = sprintf("%s/%s", $this->storageDirectory, $aggregateRootId);
            if (!is_dir($directory)) {
                mkdir($directory,0777, true);
            }

            $aggregateRootFile = sprintf("%s/%s.json", $directory, $version);
            file_put_contents($aggregateRootFile, json_encode($payload, JSON_PRETTY_PRINT));
        }
    }

    public function retrieveAll(AggregateRootId $id): Generator
    {
        $directory = sprintf("%s/%s", $this->storageDirectory, $id->toString());
        if (!is_dir($directory)) {
            echo "Directory $directory not found!\n";
            return 0;
        }

        foreach (array_diff(scandir($directory), array('..', '.')) as $file) {
//            echo "F: $file\n";
            $message = $this->messageSerializer->unserializePayload(
                json_decode(
                    file_get_contents($directory.'/'.$file),
                    true
                )
            );
print_r($message);
            yield $message;
        }

        return isset($message) ? $message->header(Header::AGGREGATE_ROOT_VERSION) : 0;
    }

    public function retrieveAllAfterVersion(AggregateRootId $id, int $aggregateRootVersion): Generator
    {
        $directory = sprintf("%s/%s", $this->storageDirectory, $id->toString());
        if (!is_dir($directory)) {
            return 0;
        }

        foreach (array_diff(scandir($directory), array('..', '.')) as $file) {
            if ($aggregateRootVersion >= (int) $file) continue;

            $message = $this->messageSerializer->unserializePayload(
                json_decode(
                    file_get_contents($directory.'/'.$file),
                    true
                )
            );

            yield $message;
        }

        return isset($message) ? $message->header(Header::AGGREGATE_ROOT_VERSION) : 0;
    }

    public function paginate(PaginationCursor $cursor): Generator
    {
        // ???
        $message = null;
        yield $message;
    }
}
