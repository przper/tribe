<?php

namespace Przper\Tribe\Shared\Infrastructure\Symfony;

use Przper\Tribe\Shared\AntiCorruption\IntegrationEventFactory;
use Przper\Tribe\Shared\AntiCorruption\IntegrationEventInterface;
use Przper\Tribe\Shared\AntiCorruption\NotSupportedIntegrationEventException;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

class IntegrationEventSerializer implements SerializerInterface
{
    /** @var array<string, IntegrationEventFactory<IntegrationEventInterface>> $integrationEventFactories */
    private array $integrationEventFactories;

    /** @param iterable<IntegrationEventFactory<IntegrationEventInterface>> $integrationEventFactories */
    public function __construct(
        #[AutowireIterator('tribe.shared.integration_event_factory')]
        iterable $integrationEventFactories,
    ) {
        foreach ($integrationEventFactories as $integrationEventFactory) {
            $this->integrationEventFactories[$integrationEventFactory->getExternalEventName()] = $integrationEventFactory;
        }
    }

    /**
     * @param array<string, mixed> $encodedEnvelope
     * @throws NotSupportedIntegrationEventException
     */
    public function decode(array $encodedEnvelope): Envelope
    {
        $body = json_decode($encodedEnvelope['body'], true);
        $eventName = $body['eventName'] ?? null;
        if (!$eventName) {
            throw new \Exception('Event name is not set!');
        }

        $integrationEvent = $this->integrationEventFactories[$eventName]->createFromArray($body);

        return new Envelope($integrationEvent);
    }

    /**
     * @return array<string, mixed>
     */
    public function encode(Envelope $envelope): array
    {
        $message = $envelope->getMessage();

        if (!$message instanceof IntegrationEventInterface) {
            $messageData = get_object_vars($message);
        } else {
            $messageData = [
                'version' => 1,
                'eventName' => $message->getEventName(),
            ];

            $reflection = new \ReflectionClass($message);
            foreach ($reflection->getProperties() as $property) {
                $property->setAccessible(true);
                $propertyName = $property->getName();
                if ($propertyName !== 'eventName') {
                    $messageData[$propertyName] = $property->getValue($message);
                }
            }
        }

        return [
            'body' => json_encode($messageData),
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ];
    }
}
