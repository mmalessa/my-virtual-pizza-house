<?php

declare(strict_types=1);

namespace Mmalessa\MessengerAddonsBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class MessengerAddonsBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new MessengerAddonsBundlePass());
    }
}
