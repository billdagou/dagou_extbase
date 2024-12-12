<?php
namespace Dagou\DagouExtbase\Event;

use Psr\Http\Message\ResponseInterface;

abstract class AbstractAttemptEvent {
    protected bool $error = FALSE;
    protected ?ResponseInterface $response = NULL;

    /**
     * @param bool $error
     *
     * @return $this
     */
    public function setError(bool $error): static {
        $this->error = $error;

        return $this;
    }

    /**
     * @return bool
     */
    public function isError(): bool {
        return $this->error;
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     *
     * @return $this
     */
    public function setResponse(ResponseInterface $response): self {
        $this->response = $response;

        return $this;
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface|null
     */
    public function getResponse(): ?ResponseInterface {
        return $this->response;
    }
}