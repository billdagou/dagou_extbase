<?php
namespace Dagou\DagouExtbase\Traits\Inject;

trait RteService {
    protected \Dagou\DagouExtbase\Service\RteService $rteService;

    /**
     * @param \Dagou\DagouExtbase\Service\RteService $rteService
     */
    public function injectRteService(\Dagou\DagouExtbase\Service\RteService $rteService) {
        $this->rteService = $rteService;
    }
}