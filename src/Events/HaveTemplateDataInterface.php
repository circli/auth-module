<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Events;

interface HaveTemplateDataInterface
{
    public function getTemplateData(): array;
}