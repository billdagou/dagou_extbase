<?php
namespace Dagou\DagouExtbase\Mvc\Controller;

class ActionController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * @return bool
     */
    protected function getErrorFlashMessage()
    {
        return FALSE;
    }
}