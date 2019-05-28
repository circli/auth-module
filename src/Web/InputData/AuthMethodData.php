<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Authentication\Web\InputData;

use Circli\Modules\Auth\Web\InputData\ProviderDataInterface;

final class AuthMethodData
{
    /** @var string */
    protected $provider;
    /** @var array */
    protected $data;

    public function __construct(string $provider, ProviderDataInterface $data)
    {
        $this->provider = $provider;
        $this->data = $data;
    }

    public function getProvider(): string
    {
        return $this->provider;
    }

    public function getAuthData(): ProviderDataInterface
    {
        return $this->data;
    }
}
