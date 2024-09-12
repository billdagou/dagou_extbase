<?php
namespace Dagou\DagouExtbase\Mvc\Web\Routing;

use Dagou\DagouExtbase\Mvc\EidRequestInterface;
use Dagou\DagouExtbase\Traits\Inject\ExtensionService;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use TYPO3\CMS\Core\Http\ApplicationType;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\HttpUtility;
use TYPO3\CMS\Extbase\DomainObject\AbstractValueObject;
use TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface;
use TYPO3\CMS\Extbase\Mvc\Exception\InvalidArgumentValueException;
use TYPO3\CMS\Extbase\Persistence\Generic\LazyLoadingProxy;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

#[Autoconfigure(public: TRUE, shared: FALSE)]
class EidUriBuilder {
    use ExtensionService;

    protected ?EidRequestInterface $request = NULL;

    protected array $arguments = [];
    protected array $lastArguments = [];
    protected string $section = '';
    protected bool $createAbsoluteUri = FALSE;
    protected string $absoluteUriScheme = '';
    protected bool $addQueryString = FALSE;
    protected array $argumentsToBeExcludedFromQueryString = [];
    protected bool $linkAccessRestrictedPages = FALSE;
    protected int $targetPageUid = 0;
    protected int $targetPageType = 0;
    protected string $language = '';
    protected bool $noCache = FALSE;
    protected string $format = '';
    protected string $argumentPrefix = '';

    /**
     * @param \Dagou\DagouExtbase\Mvc\EidRequestInterface $request
     *
     * @return $this
     */
    public function setRequest(EidRequestInterface $request): self {
        $contentObject = GeneralUtility::makeInstance(ContentObjectRenderer::class);

        $this->request = $request->withAttribute('currentContentObject', $contentObject);
        $contentObject->setRequest($this->request);

        return $this;
    }

    /**
     * @param array $arguments
     *
     * @return $this
     */
    public function setArguments(array $arguments): self {
        $this->arguments = $arguments;

        return $this;
    }

    /**
     * @param string $section
     *
     * @return $this
     */
    public function setSection(string $section): self {
        $this->section = $section;

        return $this;
    }

    /**
     * @param bool $createAbsoluteUri
     *
     * @return $this
     */
    public function setCreateAbsoluteUri(bool $createAbsoluteUri): self {
        $this->createAbsoluteUri = $createAbsoluteUri;

        return $this;
    }

    /**
     * @param string $absoluteUriScheme
     *
     * @return $this
     */
    public function setAbsoluteUriScheme(string $absoluteUriScheme): self {
        $this->absoluteUriScheme = $absoluteUriScheme;

        return $this;
    }

    /**
     * @param bool $addQueryString
     *
     * @return $this
     */
    public function setAddQueryString(bool $addQueryString): self {
        $this->addQueryString = $addQueryString;

        return $this;
    }

    /**
     * @param array $argumentsToBeExcludedFromQueryString
     *
     * @return $this
     */
    public function setArgumentsToBeExcludedFromQueryString(array $argumentsToBeExcludedFromQueryString): self {
        $this->argumentsToBeExcludedFromQueryString = $argumentsToBeExcludedFromQueryString;

        return $this;
    }

    /**
     * @param bool $linkAccessRestrictedPages
     *
     * @return $this
     */
    public function setLinkAccessRestrictedPages(bool $linkAccessRestrictedPages): self {
        $this->linkAccessRestrictedPages = $linkAccessRestrictedPages;

        return $this;
    }

    /**
     * @param int $targetPageUid
     *
     * @return $this
     */
    public function setTargetPageUid(int $targetPageUid): self {
        $this->targetPageUid = $targetPageUid;

        return $this;
    }

    /**
     * @param int $targetPageType
     *
     * @return $this
     */
    public function setTargetPageType(int $targetPageType): self {
        $this->targetPageType = $targetPageType;

        return $this;
    }

    /**
     * @param string $language
     *
     * @return $this
     */
    public function setLanguage(string $language): self {
        $this->language = $language;

        return $this;
    }

    /**
     * @param bool $noCache
     *
     * @return $this
     */
    public function setNoCache(bool $noCache): self {
        $this->noCache = $noCache;

        return $this;
    }

    /**
     * @param string $format
     *
     * @return $this
     */
    public function setFormat(string $format): self {
        $this->format = $format;

        return $this;
    }

    /**
     * @param string $argumentPrefix
     *
     * @return $this
     */
    public function setArgumentPrefix(string $argumentPrefix): self {
        $this->argumentPrefix = $argumentPrefix;

        return $this;
    }

    /**
     * @return $this
     */
    public function reset(): self {
        $this->arguments = [];
        $this->section = '';
        $this->createAbsoluteUri = FALSE;
        $this->absoluteUriScheme = '';
        $this->addQueryString = FALSE;
        $this->argumentsToBeExcludedFromQueryString = [];
        $this->linkAccessRestrictedPages = FALSE;
        $this->targetPageUid = 0;
        $this->targetPageType = 0;
        $this->language = '';
        $this->noCache = FALSE;
        $this->format = '';
        $this->argumentPrefix = '';

        return $this;
    }

    /**
     * @param string $actionName
     * @param array $controllerArguments
     * @param string $controllerName
     * @param string $extensionName
     * @param string $pluginName
     *
     * @return string
     * @throws \TYPO3\CMS\Extbase\Exception
     */
    public function uriFor(string $actionName, array $controllerArguments, string $controllerName, string $extensionName, string $pluginName): string {
        $controllerArguments['action'] = $actionName;
        $controllerArguments['controller'] = $controllerName;

        $isFrontend = ApplicationType::fromRequest($this->request)->isFrontend();

        if ($this->targetPageUid === NULL && $isFrontend) {
            $this->targetPageUid = $this->extensionService->getTargetPidByPlugin($extensionName, $pluginName);
        }

        if ($this->format !== '') {
            $controllerArguments['format'] = $this->format;
        }

        if ($this->argumentPrefix !== '') {
            $prefixedControllerArguments = [$this->argumentPrefix => $controllerArguments];
        } elseif (!$isFrontend) {
            $prefixedControllerArguments = $controllerArguments;
        } else {
            $pluginNamespace = $this->extensionService->getPluginNamespace($extensionName, $pluginName);
            $prefixedControllerArguments = [$pluginNamespace => $controllerArguments];
        }

        ArrayUtility::mergeRecursiveWithOverrule($this->arguments, $prefixedControllerArguments);

        return $this->build();
    }

    /**
     * @return string
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\InvalidArgumentValueException
     */
    public function build(): string {
        return $this->buildFrontendUri();
    }

    /**
     * @return string
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\InvalidArgumentValueException
     */
    public function buildFrontendUri(): string {
        $typolinkConfiguration = $this->buildTypolinkConfiguration();

        if ($this->createAbsoluteUri === TRUE) {
            $typolinkConfiguration['forceAbsoluteUrl'] = TRUE;

            if ($this->absoluteUriScheme !== '') {
                $typolinkConfiguration['forceAbsoluteUrl.']['scheme'] = $this->absoluteUriScheme;
            }
        }

        return $this->request->getAttribute('currentContentObject')?->createUrl($typolinkConfiguration) ?? '';
    }

    /**
     * @return array
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\InvalidArgumentValueException
     */
    protected function buildTypolinkConfiguration(): array {
        $typolinkConfiguration = [
            'parameter' => $this->targetPageUid ?? $GLOBALS['TSFE']?->id ?? '',
        ];

        if ($this->targetPageType !== 0) {
            $typolinkConfiguration['parameter'] .= ','.$this->targetPageType;
        } elseif ($this->format !== '') {
            $targetPageType = $this->extensionService->getTargetPageTypeByFormat(NULL, $this->format);
            $typolinkConfiguration['parameter'] .= ','.$targetPageType;
        }

        if (!empty($this->arguments)) {
            $arguments = $this->convertDomainObjectsToIdentityArrays($this->arguments);
            $this->lastArguments = $arguments;
            $typolinkConfiguration['additionalParams'] = HttpUtility::buildQueryString($arguments, '&');
        }

        if ($this->addQueryString) {
            $typolinkConfiguration['addQueryString'] = $this->addQueryString;

            if (!empty($this->argumentsToBeExcludedFromQueryString)) {
                $typolinkConfiguration['addQueryString.'] = [
                    'exclude' => implode(',', $this->argumentsToBeExcludedFromQueryString),
                ];
            }
        }

        if ($this->language !== '') {
            $typolinkConfiguration['language'] = $this->language;
        }

        if ($this->noCache === TRUE) {
            $typolinkConfiguration['no_cache'] = 1;
        }

        if ($this->section !== '') {
            $typolinkConfiguration['section'] = $this->section;
        }

        if ($this->linkAccessRestrictedPages === TRUE) {
            $typolinkConfiguration['linkAccessRestrictedPages'] = 1;
        }

        return $typolinkConfiguration;
    }

    /**
     * @param array $arguments
     *
     * @return array
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\InvalidArgumentValueException
     */
    protected function convertDomainObjectsToIdentityArrays(array $arguments): array {
        foreach ($arguments as $argumentKey => $argumentValue) {
            if ($argumentValue instanceof LazyLoadingProxy) {
                $argumentValue = $argumentValue->_loadRealInstance();

                $arguments[$argumentKey] = $argumentValue;
            }

            if ($argumentValue instanceof \Iterator) {
                $argumentValue = $this->convertIteratorToArray($argumentValue);
            }

            if ($argumentValue instanceof DomainObjectInterface) {
                if ($argumentValue->getUid() !== NULL) {
                    $arguments[$argumentKey] = $argumentValue->getUid();
                } elseif ($argumentValue instanceof AbstractValueObject) {
                    $arguments[$argumentKey] = $this->convertTransientObjectToArray($argumentValue);
                } else {
                    throw new InvalidArgumentValueException('Could not serialize Domain Object ' . get_class($argumentValue) . '. It is neither an Entity with identity properties set, nor a Value Object.', 1260881688);
                }
            } elseif (is_array($argumentValue)) {
                $arguments[$argumentKey] = $this->convertDomainObjectsToIdentityArrays($argumentValue);
            }
        }

        return $arguments;
    }

    /**
     * @param \Iterator $iterator
     *
     * @return array
     */
    protected function convertIteratorToArray(\Iterator $iterator): array {
        if (method_exists($iterator, 'toArray')) {
            return $iterator->toArray();
        } else {
            return iterator_to_array($iterator);
        }
    }

    /**
     * @param \TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface $object
     *
     * @return array
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\InvalidArgumentValueException
     */
    public function convertTransientObjectToArray(DomainObjectInterface $object): array {
        $result = [];

        foreach ($object->_getProperties() as $propertyName => $propertyValue) {
            if ($propertyValue instanceof \Iterator) {
                $propertyValue = $this->convertIteratorToArray($propertyValue);
            }

            if ($propertyValue instanceof DomainObjectInterface) {
                if ($propertyValue->getUid() !== NULL) {
                    $result[$propertyName] = $propertyValue->getUid();
                } else {
                    $result[$propertyName] = $this->convertTransientObjectToArray($propertyValue);
                }
            } elseif (is_array($propertyValue)) {
                $result[$propertyName] = $this->convertDomainObjectsToIdentityArrays($propertyValue);
            } else {
                $result[$propertyName] = $propertyValue;
            }
        }

        return $result;
    }
}