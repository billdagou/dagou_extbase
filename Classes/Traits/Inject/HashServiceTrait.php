<?php
namespace Dagou\DagouExtbase\Traits\Inject;

use TYPO3\CMS\Extbase\Security\Cryptography\HashService;

trait HashServiceTrait {
    protected HashService $hashService;

    /**
     * @param \TYPO3\CMS\Extbase\Security\Cryptography\HashService $hashService
     */
    public function injectHashService(HashService $hashService): void {
        $this->hashService = $hashService;
    }
}