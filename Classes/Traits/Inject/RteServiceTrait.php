<?php
namespace Dagou\DagouExtbase\Traits\Inject;

use Dagou\DagouExtbase\Service\RteService;

trait RteServiceTrait {
    protected RteService $rteService;

    /**
     * @param \Dagou\DagouExtbase\Service\RteService $rteService
     */
    public function injectRteService(RteService $rteService): void {
        $this->rteService = $rteService;
    }
}