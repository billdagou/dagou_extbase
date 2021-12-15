<?php
namespace Dagou\DagouExtbase\Traits\Inject;

use Psr\EventDispatcher\EventDispatcherInterface;

trait EventDispatcher {
    /**
     * @var \Psr\EventDispatcher\EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @param \Psr\EventDispatcher\EventDispatcherInterface $eventDispatcher
     */
    public function injectEventDispatcher(EventDispatcherInterface $eventDispatcher) {
        $this->eventDispatcher = $eventDispatcher;
    }
}