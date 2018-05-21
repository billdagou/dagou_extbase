<?php
namespace Dagou\DagouExtbase\Http;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Exception;
use TYPO3\CMS\Core\Http\Dispatcher;
use TYPO3\CMS\Core\Http\Response;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Core\Bootstrap;
use TYPO3\CMS\Frontend\Http\RequestHandler;

class EidRequestHandler extends RequestHandler {
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return null|\Psr\Http\Message\ResponseInterface|\TYPO3\CMS\Core\Http\Response
     * @throws \TYPO3\CMS\Core\Error\Http\ServiceUnavailableException
     */
    public function handleRequest(ServerRequestInterface $request) {
        $response = NULL;
        $this->request = $request;
        $this->initializeTimeTracker();

        // Hook to preprocess the current request:
        if (is_array($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/index_ts.php']['preprocessRequest'])) {
            foreach ($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/index_ts.php']['preprocessRequest'] as $hookFunction) {
                $hookParameters = [];
                GeneralUtility::callUserFunction($hookFunction, $hookParameters, $hookParameters);
            }
            unset($hookFunction);
            unset($hookParameters);
        }

        //$this->initializeController();

        if ($GLOBALS['TYPO3_CONF_VARS']['FE']['pageUnavailable_force']
            && !GeneralUtility::cmpIP(
                GeneralUtility::getIndpEnv('REMOTE_ADDR'),
                $GLOBALS['TYPO3_CONF_VARS']['SYS']['devIPmask']
            )) {
            $this->controller->pageUnavailableAndExit('This page is temporarily unavailable.');
        }

        //$this->controller->connectToDB();

        // Output compression
        // Remove any output produced until now
        $this->bootstrap->endOutputBufferingAndCleanPreviousOutput();

        /** @var \TYPO3\CMS\Core\Http\Response $response */
        $response = GeneralUtility::makeInstance(Response::class);

        $eID = isset($request->getParsedBody()['eID']) ? $request->getParsedBody()['eID'] :
            (isset($request->getQueryParams()['eID']) ? $request->getQueryParams()['eID'] : '');

        if (empty($eID) || !isset($GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include'][$eID])) {
            return $response->withStatus(404, 'eID not registered');
        }

        if (!is_array($GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include'][$eID])) {
            $configuration = $GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include'][$eID];

            // Simple check to make sure that it's not an absolute file (to use the fallback)
            if (strpos($configuration, '::') !== FALSE || is_callable($configuration)) {
                /** @var \TYPO3\CMS\Core\Http\Dispatcher $dispatcher */
                $dispatcher = GeneralUtility::makeInstance(Dispatcher::class);
                $request = $request->withAttribute('target', $configuration);

                return $dispatcher->dispatch($request, $response);
            }

            $scriptPath = GeneralUtility::getFileAbsFileName($configuration);
            if ($scriptPath === '') {
                throw new Exception('Registered eID has invalid script path.', 1416391467);
            }
            include $scriptPath;

            return NULL;
        }

        $this->initializeController();

        $this->controller->connectToDB();

        $this->initializeOutputCompression();

        $this->bootstrap->loadBaseTca();

        // Initializing the Frontend User
        $this->timeTracker->push('Front End user initialized', '');
        $this->controller->initFEuser();
        $this->timeTracker->pull();

        // Initializing a possible logged-in Backend User
        /** @var $GLOBALS ['BE_USER'] \TYPO3\CMS\Backend\FrontendBackendUserAuthentication */
        //$GLOBALS['BE_USER'] = $this->controller->initializeBackendUser();

        // Process the ID, type and other parameters.
        // After this point we have an array, $page in TSFE, which is the page-record
        // of the current page, $id.
        $this->timeTracker->push('Process ID', '');
        // Initialize admin panel since simulation settings are required here:
        /*if ($this->controller->isBackendUserLoggedIn()) {
            $GLOBALS['BE_USER']->initializeAdminPanel();
            $this->bootstrap->initializeBackendRouter()->loadExtTables();
        }*/
        $this->controller->checkAlternativeIdMethods();
        $this->controller->clear_preview();
        $this->controller->determineId();

        // Now, if there is a backend user logged in and he has NO access to this page,
        // then re-evaluate the id shown! _GP('ADMCMD_noBeUser') is placed here because
        // \TYPO3\CMS\Version\Hook\PreviewHook might need to know if a backend user is logged in.
        /*if ($this->controller->isBackendUserLoggedIn()
            && (!$GLOBALS['BE_USER']->extPageReadAccess($this->controller->page)
                || GeneralUtility::_GP(
                    'ADMCMD_noBeUser'
                ))) {
            // Remove user
            unset($GLOBALS['BE_USER']);
            $this->controller->beUserLogin = FALSE;
            // Re-evaluate the page-id.
            $this->controller->checkAlternativeIdMethods();
            $this->controller->clear_preview();
            $this->controller->determineId();
        }*/

        //$this->controller->makeCacheHash();
        $this->timeTracker->pull();

        // Admin Panel & Frontend editing
        /*if ($this->controller->isBackendUserLoggedIn()) {
            $GLOBALS['BE_USER']->initializeFrontendEdit();
            if ($GLOBALS['BE_USER']->adminPanel instanceof AdminPanelView) {
                $this->bootstrap->initializeLanguageObject();
            }
            if ($GLOBALS['BE_USER']->frontendEdit instanceof FrontendEditingController) {
                $GLOBALS['BE_USER']->frontendEdit->initConfigOptions();
            }
        }*/

        // Starts the template
        $this->timeTracker->push('Start Template', '');
        $this->controller->initTemplate();
        $this->timeTracker->pull();
        // Get from cache
        /*$this->timeTracker->push('Get Page from cache', '');
        $this->controller->getFromCache();
        $this->timeTracker->pull();*/
        // Get config if not already gotten
        // After this, we should have a valid config-array ready
        $this->controller->getConfigArray();
        // Setting language and locale
        $this->timeTracker->push('Setting language and locale', '');
        $this->controller->settingLanguage();
        $this->controller->settingLocale();
        $this->timeTracker->pull();

        // Convert POST data to utf-8 for internal processing if metaCharset is different
        $this->controller->convPOSTCharset();

        //$this->controller->initializeRedirectUrlHandlers();

        //$this->controller->handleDataSubmission();

        // Check for shortcut page and redirect
        /*$this->controller->checkPageForShortcutRedirect();
        $this->controller->checkPageForMountpointRedirect();*/

        // Generate page
        //$this->controller->setUrlIdToken();
        $this->timeTracker->push('Page generation', '');
        /*if ($this->controller->isGeneratePage()) {
            $this->controller->generatePage_preProcessing();
            $temp_theScript = $this->controller->generatePage_whichScript();
            if ($temp_theScript) {
                include $temp_theScript;
            } else {
                $this->controller->preparePageContentGeneration();
                // Content generation
                if (!$this->controller->isINTincScript()) {
                    PageGenerator::renderContent();
                    $this->controller->setAbsRefPrefix();
                }
            }
            $this->controller->generatePage_postProcessing();
        } elseif ($this->controller->isINTincScript()) {
            $this->controller->preparePageContentGeneration();
        }*/
        $this->controller->newCObj();
        //$this->controller->releaseLocks();
        $this->timeTracker->pull();

        // Render non-cached parts
        /*if ($this->controller->isINTincScript()) {
            $this->timeTracker->push('Non-cached objects', '');
            $this->controller->INTincScript();
            $this->timeTracker->pull();
        }*/

        // Output content
        //$sendTSFEContent = FALSE;
        //if ($this->controller->isOutputting()) {
            $this->timeTracker->push('Print Content', '');
            //$this->controller->processOutput();
            $this->processOutput($eID);
            //$sendTSFEContent = TRUE;
            $this->timeTracker->pull();
        //}
        // Store session data for fe_users
        $this->controller->storeSessionData();
        // Statistics
        /*$GLOBALS['TYPO3_MISC']['microtime_end'] = microtime(TRUE);
        if ($this->controller->isOutputting()) {
            if (isset($this->controller->config['config']['debug'])) {
                $debugParseTime = (bool)$this->controller->config['config']['debug'];
            } else {
                $debugParseTime = !empty($GLOBALS['TYPO3_CONF_VARS']['FE']['debug']);
            }
            if ($debugParseTime) {
                $this->controller->content .= LF.'<!-- Parsetime: '.$this->timeTracker->getParseTime().'ms -->';
            }
        }
        $this->controller->redirectToExternalUrl();*/
        // Preview info
        //$this->controller->previewInfo();
        // Hook for end-of-frontend
        //$this->controller->hook_eofe();
        // Finish timetracking
        $this->timeTracker->pull();

        // Admin panel
        /*if ($this->controller->isBackendUserLoggedIn()
            && $GLOBALS['BE_USER'] instanceof FrontendBackendUserAuthentication) {
            if ($GLOBALS['BE_USER']->isAdminPanelVisible()) {
                $this->controller->content = str_ireplace(
                    '</body>',
                    $GLOBALS['BE_USER']->displayAdminPanel().'</body>',
                    $this->controller->content
                );
            }
        }*/

        //if ($sendTSFEContent) {
            /** @var \TYPO3\CMS\Core\Http\Response $response */
            //$response = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Http\Response::class);
            // Send content-length header.
            // Notice that all HTML content outside the length of the content-length header will be cut off!
            // Therefore content of unknown length from included PHP-scripts and if admin users are logged
            // in (admin panel might show...) or if debug mode is turned on, we disable it!
            if ((!isset($this->controller->config['config']['enableContentLengthHeader'])
                    || $this->controller->config['config']['enableContentLengthHeader'])
                && !$this->controller->beUserLogin
                && !$GLOBALS['TYPO3_CONF_VARS']['FE']['debug']
                && !$this->controller->config['config']['debug']
                && !$this->controller->doWorkspacePreview()) {
                header('Content-Length: '.strlen($this->controller->content));
            }
            $response->getBody()->write($this->controller->content);
        //}
        // Debugging Output
        /*if (isset($GLOBALS['error']) && is_object($GLOBALS['error'])
            && @is_callable(
                [$GLOBALS['error'], 'debugOutput']
            )) {
            $GLOBALS['error']->debugOutput();
        }
        GeneralUtility::devLog('END of FRONTEND session', 'cms', 0, ['_FLUSH' => TRUE]);*/

        return $response;
    }

    protected function processOutput($eID) {
        $bootstrap = GeneralUtility::makeInstance(Bootstrap::class);

        $configuration = $GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include'][$eID];
        ArrayUtility::mergeRecursiveWithOverrule(
            $configuration,
            [
                'features' => [
                    'requireCHashArgumentForActionArguments' => FALSE,
                ],
            ]
        );

        $this->controller->content = $bootstrap->run('', $configuration);
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return bool
     */
    public function canHandleRequest(ServerRequestInterface $request) {
        return !empty($request->getQueryParams()['eID']) || !empty($request->getParsedBody()['eID']);
    }

    /**
     * @return int
     */
    public function getPriority() {
        return 80;
    }
}