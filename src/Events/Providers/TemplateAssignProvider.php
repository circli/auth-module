<?php declare(strict_types=1);

namespace Circli\Modules\Auth\Events\Providers;

use Circli\Extensions\Template\Events\PreRenderEvent;
use Circli\Modules\Auth\Events\HaveTemplateDataInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

final class TemplateAssignProvider implements ListenerProviderInterface
{
    private $data = [];

    /**
     * @param object $event
     *   An event for which to return the relevant listeners.
     * @return iterable[callable]
     *   An iterable (array, iterator, or generator) of callables.  Each
     *   callable MUST be type-compatible with $event.
     */
    public function getListenersForEvent(object $event): iterable
    {
        if ($event instanceof HaveTemplateDataInterface) {
            yield from [function(HaveTemplateDataInterface $event) {
                $this->data = array_merge($this->data, $event->getTemplateData());
            }];
        }
        if ($this->data && $event instanceof PreRenderEvent) {
            yield from [function(PreRenderEvent $event) {
                $event->getTemplate()->assign($this->data);
            }];
        }
    }
}