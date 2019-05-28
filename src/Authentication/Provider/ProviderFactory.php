<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Authentication\Provider;

use Circli\Modules\Auth\Authentication\Exception\ProviderNotFound;

final class ProviderFactory implements ProviderFactoryInterface
{
    /** @var callable */
    private $resolver;
    private $map = [
        'password' => Password::class,
        'token' => Token::class,
    ];

    public function __construct(callable $resolver)
    {
        $this->resolver = $resolver;
    }

    public function resolveProvider(string $provider): ProviderInterface
    {
        if (!isset($this->map[$provider])) {
            throw new ProviderNotFound($provider);
        }

        return ($this->resolver)($this->map[$provider]);
    }
}
