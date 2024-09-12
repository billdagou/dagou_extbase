<?php
namespace Dagou\DagouExtbase\Mvc;

use Psr\Http\Message\ServerRequestInterface;

interface EidRequestInterface extends ServerRequestInterface {
    /**
     * @return string
     */
    public function getEid(): string;

    /**
     * @return string
     */
    public function getControllerActionName(): string;

    /**
     * @param string $argumentName
     *
     * @return mixed
     */
    public function getArgument(string $argumentName): mixed;

    /**
     * @param string $argumentName
     *
     * @return bool
     */
    public function hasArgument(string $argumentName): bool;
}