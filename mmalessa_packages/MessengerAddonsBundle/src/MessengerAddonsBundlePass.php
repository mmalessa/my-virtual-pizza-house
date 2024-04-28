<?php

declare(strict_types=1);

namespace Mmalessa\MessengerAddonsBundle;

use HaydenPierce\ClassFinder\ClassFinder;
use Mmalessa\MessengerAddonsBundle\ExternalMessageMapper\AsExternalMessage;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class MessengerAddonsBundlePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void {
        ClassFinder::disablePSR4Vendors();
        $mappers = $container->findTaggedServiceIds('mmalessa.external_message_mapper');
        foreach ($mappers as $serviceId => $tags) {
            foreach ($tags as $tag) {
                $namespace = $tag['namespace'];
                $serviceDefinition = $container->getDefinition($serviceId);
                $messageClasseNames = ClassFinder::getClassesInNamespace($namespace, ClassFinder::RECURSIVE_MODE);
                foreach ($messageClasseNames as $messageClassName) {
                    $reflection = new \ReflectionClass($messageClassName);
                    $attributes = $reflection->getAttributes(AsExternalMessage::class);
                    if (count($attributes) === 0) {
                        continue;
                    }
                    $attribute = $attributes[0];
                    $attributeArguments = $attribute->getArguments();
                    if (!array_key_exists('schemaId', $attributeArguments)) {
                        continue;
                    }
                    $messageSchemaId = $attributeArguments['schemaId'];
                    $serviceDefinition->addMethodCall('register', [$messageClassName, $messageSchemaId]);
                }
            }
        }
    }
}
