<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Authentication\Provider;

interface ProviderFactoryInterface
{
    public function resolveProvider(string $provider): ProviderInterface;
}