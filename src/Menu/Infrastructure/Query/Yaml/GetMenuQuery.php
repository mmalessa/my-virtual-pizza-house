<?php

declare(strict_types=1);

namespace App\Menu\Infrastructure\Query\Yaml;
use App\Menu\Domain\Query\GetMenuQueryInterface;
use Symfony\Component\Yaml\Yaml;

class GetMenuQuery implements GetMenuQueryInterface
{
    public function __construct(
        private readonly string $menuYamlFile
    )
    {
    }

    public function getMenu(): array
    {
        $menu = Yaml::parseFile($this->menuYamlFile);
        return $menu['menu'];
    }
}