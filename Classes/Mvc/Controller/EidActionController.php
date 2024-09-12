<?php
namespace Dagou\DagouExtbase\Mvc\Controller;

use Dagou\DagouExtbase\Mvc\EidRequest;
use Dagou\DagouExtbase\Mvc\EidRequestInterface;
use Dagou\DagouExtbase\Mvc\Web\EidRequestBuilder;
use Dagou\DagouExtbase\Traits\Inject\EventDispatcher;
use Dagou\DagouExtbase\Traits\Inject\PropertyMapper;
use Dagou\DagouExtbase\Traits\Inject\ReflectionService;
use Dagou\DagouExtbase\Traits\Inject\ValidatorResolver;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Http\HtmlResponse;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Event\Mvc\BeforeActionCallEvent;
use TYPO3\CMS\Extbase\Mvc\Controller\Argument;
use TYPO3\CMS\Extbase\Mvc\Controller\Arguments;
use TYPO3\CMS\Extbase\Mvc\Controller\Exception\RequiredArgumentMissingException;
use TYPO3\CMS\Extbase\Mvc\Exception\InvalidArgumentTypeException;
use TYPO3\CMS\Extbase\Mvc\Exception\NoSuchActionException;
use TYPO3\CMS\Extbase\Property\Exception\TargetNotFoundException;
use TYPO3\CMS\Extbase\Validation\Validator\ConjunctionValidator;

abstract class EidActionController implements EidControllerInterface {
    use EventDispatcher, PropertyMapper, ReflectionService, ValidatorResolver;

    protected string $actionMethodName = '';
    protected string $errorMethodName = 'errorAction';
    protected ?EidRequest $request = NULL;
    protected ?Arguments $arguments = NULL;

    protected function initializeAction(): void {}

    /**
     * @return void
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\InvalidArgumentTypeException
     * @throws \TYPO3\CMS\Extbase\Reflection\ClassSchema\Exception\NoSuchMethodException
     * @throws \TYPO3\CMS\Extbase\Reflection\Exception\UnknownClassException
     */
    protected function initializeActionMethodArguments(): void {
        $methodParameters = $this->reflectionService->getClassSchema(static::class)
            ->getMethod($this->actionMethodName)
                ->getParameters();

        foreach ($methodParameters as $parameterName => $parameter) {
            $dataType = NULL;

            if ($parameter->getType() !== NULL) {
                $dataType = $parameter->getType();
            } elseif ($parameter->isArray()) {
                $dataType = 'array';
            }

            if ($dataType === NULL) {
                throw new InvalidArgumentTypeException('The argument type for parameter $'.$parameterName.' of method '.static::class.'->'.$this->actionMethodName.'() could not be detected.', 1253175643);
            }

            $this->arguments->addNewArgument(
                $parameterName,
                $dataType,
                !$parameter->isOptional(),
                $parameter->hasDefaultValue() ? $parameter->getDefaultValue() : NULL
            );
        }
    }

    /**
     * @return void
     * @throws \TYPO3\CMS\Extbase\Reflection\ClassSchema\Exception\NoSuchMethodException
     * @throws \TYPO3\CMS\Extbase\Reflection\ClassSchema\Exception\NoSuchMethodParameterException
     * @throws \TYPO3\CMS\Extbase\Reflection\Exception\UnknownClassException
     */
    protected function initializeActionMethodValidators(): void {
        if ($this->arguments->count() === 0) {
            return;
        }

        $classSchemaMethod = $this->reflectionService->getClassSchema(static::class)
            ->getMethod($this->actionMethodName);

        /** @var \TYPO3\CMS\Extbase\Mvc\Controller\Argument $argument */
        foreach ($this->arguments as $argument) {
            $classSchemaMethodParameter = $classSchemaMethod->getParameter($argument->getName());

            if ($classSchemaMethodParameter->ignoreValidation()) {
                continue;
            }

            /** @var \TYPO3\CMS\Extbase\Validation\Validator\ConjunctionValidator $validator */
            $validator = $this->validatorResolver->createValidator(ConjunctionValidator::class);
            foreach ($classSchemaMethodParameter->getValidators() as $validatorDefinition) {
                $validator->addValidator(
                    $this->validatorResolver->createValidator($validatorDefinition['className'], $validatorDefinition['options'])
                );
            }

            $baseValidatorConjunction = $this->validatorResolver->getBaseValidatorConjunction($argument->getDataType());
            if ($baseValidatorConjunction->count() > 0) {
                $validator->addValidator($baseValidatorConjunction);
            }

            $argument->setValidator($validator);
        }
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \TYPO3\CMS\Extbase\Mvc\Controller\Exception\RequiredArgumentMissingException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\InvalidActionNameException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\InvalidArgumentNameException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\InvalidArgumentTypeException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\NoSuchActionException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\NoSuchArgumentException
     * @throws \TYPO3\CMS\Extbase\Property\Exception
     * @throws \TYPO3\CMS\Extbase\Property\Exception\TargetNotFoundException
     * @throws \TYPO3\CMS\Extbase\Reflection\ClassSchema\Exception\NoSuchMethodException
     * @throws \TYPO3\CMS\Extbase\Reflection\ClassSchema\Exception\NoSuchMethodParameterException
     * @throws \TYPO3\CMS\Extbase\Reflection\Exception\UnknownClassException
     */
    public function processRequest(ServerRequestInterface $request): ResponseInterface {
        $request = GeneralUtility::makeInstance(EidRequestBuilder::class)->build($request);

        $this->arguments = GeneralUtility::makeInstance(Arguments::class);
        $this->request = $request;
        $this->actionMethodName = $this->resolveActionMethodName();

        $this->initializeActionMethodArguments();
        $this->initializeActionMethodValidators();
        $this->initializeAction();

        $actionInitializationMethodName = 'initialize' . ucfirst($this->actionMethodName);
        /** @var callable $callable */
        $callable = [$this, $actionInitializationMethodName];
        if (is_callable($callable)) {
            $callable();
        }

        $this->mapRequestArgumentsToControllerArguments();

        return $this->callActionMethod($request);
    }

    /**
     * @return string
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\NoSuchActionException
     */
    protected function resolveActionMethodName(): string {
        $actionMethodName = $this->request->getControllerActionName().'Action';

        if (!method_exists($this, $actionMethodName)) {
            throw new NoSuchActionException('An action "'.$actionMethodName.'" does not exist in controller "'.static::class.'".', 1186669086);
        }

        return $actionMethodName;
    }

    /**
     * @param \Dagou\DagouExtbase\Mvc\EidRequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function callActionMethod(EidRequestInterface $request): ResponseInterface {
        $preparedArguments = [];
        /** @var \TYPO3\CMS\Extbase\Mvc\Controller\Argument $argument */
        foreach ($this->arguments as $argument) {
            $preparedArguments[] = $argument->getValue();
        }

        $validationResult = $this->arguments->validate();
        if (!$validationResult->hasErrors()) {
            $this->eventDispatcher->dispatch(new BeforeActionCallEvent(static::class, $this->actionMethodName, $preparedArguments));
            $actionResult = $this->{$this->actionMethodName}(...$preparedArguments);
        } else {
            $actionResult = $this->{$this->errorMethodName}();
        }

        if ($actionResult instanceof ResponseInterface) {
            return $actionResult;
        }

        throw new \RuntimeException(
            sprintf(
                'Controller action %s did not return an instance of %s.',
                static::class.'::'.$this->actionMethodName,
                ResponseInterface::class
            ),
            1726106674
        );
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function errorAction(): ResponseInterface {
        return new HtmlResponse(
            'Validation failed while trying to call '.static::class.'->'.$this->actionMethodName.'().',
            400
        );
    }

    /**
     * @return void
     * @throws \TYPO3\CMS\Extbase\Mvc\Controller\Exception\RequiredArgumentMissingException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\NoSuchArgumentException
     * @throws \TYPO3\CMS\Extbase\Property\Exception
     * @throws \TYPO3\CMS\Extbase\Property\Exception\TargetNotFoundException
     */
    protected function mapRequestArgumentsToControllerArguments(): void {
        /** @var \TYPO3\CMS\Extbase\Mvc\Controller\Argument $argument */
        foreach ($this->arguments as $argument) {
            $argumentName = $argument->getName();

            if ($this->request->hasArgument($argumentName)) {
                $this->setArgumentValue($argument, $this->request->getArgument($argumentName));
            } elseif ($argument->isRequired()) {
                throw new RequiredArgumentMissingException('Required argument "'.$argumentName.'" is not set for '.static::class.'->'.$this->actionMethodName.'().', 1298012500);
            }
        }
    }

    /**
     * @param \TYPO3\CMS\Extbase\Mvc\Controller\Argument $argument
     * @param $rawValue
     *
     * @return void
     * @throws \TYPO3\CMS\Extbase\Property\Exception
     * @throws \TYPO3\CMS\Extbase\Property\Exception\TargetNotFoundException
     */
    private function setArgumentValue(Argument $argument, $rawValue): void {
        if ($rawValue === NULL) {
            $argument->setValue(NULL);

            return;
        }

        $dataType = $argument->getDataType();
        if ($rawValue instanceof $dataType) {
            $argument->setValue($rawValue);

            return;
        }

        $this->propertyMapper->resetMessages();

        try {
            $argument->setValue(
                $this->propertyMapper->convert(
                    $rawValue,
                    $dataType,
                    $argument->getPropertyMappingConfiguration()
                )
            );
        } catch (TargetNotFoundException $e) {
            if ($argument->isRequired()) {
                throw $e;
            }
        }

        $argument->getValidationResults()->merge($this->propertyMapper->getMessages());
    }
}