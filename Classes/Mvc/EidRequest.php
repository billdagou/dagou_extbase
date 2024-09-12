<?php
namespace Dagou\DagouExtbase\Mvc;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

class EidRequest implements EidRequestInterface {
    protected ServerRequestInterface $request;

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     */
    public function __construct(ServerRequestInterface $request) {
        $this->request = $request;
    }

    /**
     * @return \Dagou\DagouExtbase\Mvc\EidRequestParameters
     */
    protected function getEidAttribute(): EidRequestParameters {
        return $this->request->getAttribute('eid');
    }

    /**
     * @return string
     */
    public function getEid(): string {
        return $this->getEidAttribute()->getEid();
    }

    /**
     * @return string
     */
    public function getControllerActionName(): string {
        return $this->getEidAttribute()->getControllerActionName();
    }

    /**
     * @param string $argumentName
     *
     * @return mixed
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\NoSuchArgumentException
     */
    public function getArgument(string $argumentName): mixed {
        return $this->getEidAttribute()->getArgument($argumentName);
    }

    /**
     * @param string $argumentName
     *
     * @return bool
     */
    public function hasArgument(string $argumentName): bool {
        return $this->getEidAttribute()->hasArgument($argumentName);
    }

    // Methods implementing ServerRequestInterface
    /**
     * @return array
     */
    public function getServerParams(): array {
        return $this->request->getServerParams();
    }

    /**
     * @return array
     */
    public function getCookieParams(): array {
        return $this->request->getCookieParams();
    }

    /**
     * @param array $cookies
     *
     * @return $this
     */
    public function withCookieParams(array $cookies): self {
        return new static(
            $this->request->withCookieParams($cookies)
        );
    }

    /**
     * @return array
     */
    public function getQueryParams(): array {
        return $this->request->getQueryParams();
    }

    /**
     * @param array $query
     *
     * @return $this
     */
    public function withQueryParams(array $query): self {
        return new static(
            $this->request->withQueryParams($query)
        );
    }

    /**
     * @return array
     */
    public function getUploadedFiles(): array {
        return $this->getEidAttribute()->getUploadedFiles();
    }

    /**
     * @param array $uploadedFiles
     *
     * @return $this
     */
    public function withUploadedFiles(array $uploadedFiles): self {
        return $this->withAttribute('eid', (clone $this->getEidAttribute())->setUploadedFiles($uploadedFiles));
    }

    /**
     * @return array|object|null
     */
    public function getParsedBody(): array|object|null {
        return $this->request->getParsedBody();
    }

    /**
     * @param mixed $data
     *
     * @return $this
     */
    public function withParsedBody(mixed $data): self {
        return new static(
            $this->request->withParsedBody($data)
        );
    }

    /**
     * @return array
     */
    public function getAttributes(): array {
        return $this->request->getAttributes();
    }

    /**
     * @param string $name
     * @param mixed|NULL $default
     *
     * @return mixed
     */
    public function getAttribute(string $name, mixed $default = NULL): mixed {
        return $this->request->getAttribute($name, $default);
    }

    /**
     * @param string $name
     * @param mixed $value
     *
     * @return $this
     */
    public function withAttribute(string $name, mixed $value): self {
        return new static(
            $this->request->withAttribute($name, $value)
        );
    }

    /**
     * @param string $name
     *
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    public function withoutAttribute(string $name): ServerRequestInterface {
        $request = $this->request->withoutAttribute($name);

        if ($name === 'eid') {
            return $request;
        }

        return new static($request);
    }

    // Methods implementing RequestInterface
    /**
     * @return string
     */
    public function getRequestTarget(): string {
        return $this->request->getRequestTarget();
    }

    /**
     * @param $requestTarget
     *
     * @return $this
     */
    public function withRequestTarget($requestTarget): self {
        return new static(
            $this->request->withRequestTarget($requestTarget)
        );
    }

    /**
     * @return string
     */
    public function getMethod(): string {
        return $this->request->getMethod();
    }

    /**
     * @param $method
     *
     * @return $this
     */
    public function withMethod($method): self {
        return new static(
            $this->request->withMethod($method)
        );
    }

    /**
     * @return \Psr\Http\Message\UriInterface
     */
    public function getUri(): UriInterface {
        return $this->request->getUri();
    }

    /**
     * @param \Psr\Http\Message\UriInterface $uri
     * @param bool $preserveHost
     *
     * @return $this
     */
    public function withUri(UriInterface $uri, bool $preserveHost = FALSE): self {
        return new static(
            $this->request->withUri($uri, $preserveHost)
        );
    }

    // Methods implementing MessageInterface
    /**
     * @return string
     */
    public function getProtocolVersion(): string {
        return $this->request->getProtocolVersion();
    }

    /**
     * @param string $version
     *
     * @return $this
     */
    public function withProtocolVersion(string $version): self {
        return new static(
            $this->request->withProtocolVersion($version)
        );
    }

    /**
     * @return array
     */
    public function getHeaders(): array {
        return $this->request->getHeaders();
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasHeader(string $name): bool {
        return $this->request->hasHeader($name);
    }

    /**
     * @param string $name
     *
     * @return array|string[]
     */
    public function getHeader(string $name): array {
        return $this->request->getHeader($name);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function getHeaderLine(string $name): string {
        return $this->request->getHeaderLine($name);
    }

    /**
     * @param string $name
     * @param mixed $value
     *
     * @return $this
     */
    public function withHeader(string $name, mixed $value): self {
        return new static(
            $this->request->withHeader($name, $value)
        );
    }

    /**
     * @param string $name
     * @param mixed $value
     *
     * @return $this
     */
    public function withAddedHeader(string $name, mixed $value): self {
        return new static(
            $this->request->withAddedHeader($name, $value)
        );
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function withoutHeader(string $name): self {
        return new static(
            $this->request->withoutHeader($name)
        );
    }

    /**
     * @return \Psr\Http\Message\StreamInterface
     */
    public function getBody(): StreamInterface {
        return $this->request->getBody();
    }

    /**
     * @param \Psr\Http\Message\StreamInterface $body
     *
     * @return $this
     */
    public function withBody(StreamInterface $body): self {
        return new static(
            $this->request->withBody($body)
        );
    }
}