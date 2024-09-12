<?php
namespace Dagou\DagouExtbase\Mvc;

use Psr\Http\Message\UploadedFileInterface;
use TYPO3\CMS\Extbase\Mvc\Exception\InvalidArgumentNameException;
use TYPO3\CMS\Extbase\Mvc\Exception\NoSuchArgumentException;

class EidRequestParameters {
    protected string $eid = '';
    protected string $controllerActionName = 'index';
    protected array $arguments = [];
    protected array $uploadedFiles = [];

    /**
     * @param string $eid
     *
     * @return $this
     */
    public function setEid(string $eid): self {
        $this->eid = $eid;

        return $this;
    }

    /**
     * @return string
     */
    public function getEid(): string {
        return $this->eid;
    }

    /**
     * @param string $controllerActionName
     *
     * @return $this
     */
    public function setControllerActionName(string $controllerActionName): self {
        $this->controllerActionName = $controllerActionName;

        return $this;
    }

    /**
     * @return string
     */
    public function getControllerActionName(): string {
        return $this->controllerActionName;
    }

    /**
     * @param mixed $value The new value
     * @throws InvalidArgumentNameException
     */
    public function setArgument(string $argumentName, mixed $value): self {
        if ($argumentName === '') {
            throw new InvalidArgumentNameException('Invalid argument name.', 1210858767);
        }

        $this->arguments[$argumentName] = $value;

        return $this;
    }

    /**
     * @param string $argumentName
     *
     * @return mixed
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\NoSuchArgumentException
     */
    public function getArgument(string $argumentName): mixed {
        if (!isset($this->arguments[$argumentName])) {
            throw new NoSuchArgumentException('An argument "'.$argumentName.'" does not exist for this request.', 1176558158);
        }

        return $this->arguments[$argumentName];
    }

    /**
     * @param string $argumentName
     *
     * @return bool
     */
    public function hasArgument(string $argumentName = ''): bool {
        return isset($this->arguments[$argumentName]);
    }

    /**
     * @return array
     */
    public function getUploadedFiles(): array {
        return $this->uploadedFiles;
    }

    /**
     * @param array $files
     *
     * @return $this
     */
    public function setUploadedFiles(array $files): self {
        $this->validateUploadedFiles($files);

        $this->uploadedFiles = $files;

        return $this;
    }

    /**
     * @param array $uploadedFiles
     *
     * @return void
     */
    protected function validateUploadedFiles(array $uploadedFiles): void {
        foreach ($uploadedFiles as $file) {
            if (is_array($file)) {
                $this->validateUploadedFiles($file);

                continue;
            }

            if (!$file instanceof UploadedFileInterface) {
                throw new \InvalidArgumentException('Invalid file in uploaded files structure.', 1647338470);
            }
        }
    }
}