<?php
namespace Dagou\DagouExtbase\Traits\Inject;

trait HashService {
    protected \TYPO3\CMS\Extbase\Security\Cryptography\HashService $hashService;

    /**
     * @param \TYPO3\CMS\Extbase\Security\Cryptography\HashService $hashService
     */
    public function injectHashService(\TYPO3\CMS\Extbase\Security\Cryptography\HashService $hashService) {
        $this->hashService = $hashService;
    }
}