<?php
namespace Dagou\DagouExtbase\Traits\Inject;

use Psr\EventDispatcher\EventDispatcherInterface;

trait EventDispatcher {
    protected EventDispatcherInterface $eventDispatcher;

    /**
     * @param \Psr\EventDispatcher\EventDispatcherInterface $eventDispatcher
     */
    public function injectEventDispatcher(EventDispatcherInterface $eventDispatcher): void {
        $this->eventDispatcher = $eventDispatcher;
    }
}