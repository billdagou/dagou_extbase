<?php
namespace Dagou\DagouExtbase\Traits\Inject;

trait RteService {
    /**
     * @var \Dagou\DagouExtbase\Service\RteService
     */
    protected $rteService;

    /**
     * @param \Dagou\DagouExtbase\Service\RteService $rteService
     */
    public function injectRteService(\Dagou\DagouExtbase\Service\RteService $rteService) {
        $this->rteService = $rteService;
    }
}