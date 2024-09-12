<?php
namespace Dagou\DagouExtbase\Mvc\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface EidControllerInterface {
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function processRequest(ServerRequestInterface $request): ResponseInterface;
}