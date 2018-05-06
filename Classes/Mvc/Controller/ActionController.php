<?php
namespace Dagou\DagouExtbase\Mvc\Controller;

use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;

class ActionController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * @return bool
     */
    protected function getErrorFlashMessage()
    {
        return FALSE;
    }

    protected function initializeView(ViewInterface $view) {
        print_r($view->getRenderingContext()->getTemplatePaths());
    }
}