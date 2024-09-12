<?php
namespace Dagou\DagouExtbase\Mvc\Web;

use Dagou\DagouExtbase\Mvc\EidRequest;
use Dagou\DagouExtbase\Mvc\EidRequestParameters;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Http\UploadedFile;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Extbase\Mvc\Exception\InvalidActionNameException;

class EidRequestBuilder implements SingletonInterface {
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $mainRequest
     *
     * @return \Dagou\DagouExtbase\Mvc\EidRequest
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\InvalidActionNameException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\InvalidArgumentNameException
     */
    public function build(ServerRequestInterface $mainRequest): EidRequest {
        $parameters = $mainRequest->getQueryParams();

        if ($mainRequest->getMethod() === 'POST') {
            $postParameters = $mainRequest->getParsedBody();

            $postParameters = is_array($postParameters) ? $postParameters : [];

            $parameters = array_replace_recursive($parameters, $postParameters);
        }

        $files = $mainRequest->getUploadedFiles();
        if ($files instanceof UploadedFile) {
            $files = [$files];
        }
        $fileParameters = $this->mapUploadedFilesToParameters($files, []);
        if (count($fileParameters) === 1) {
            $fileParameters = reset($fileParameters);
        }
        $parameters = array_replace_recursive($parameters, $fileParameters);

        $eidAttribute = (new EidRequestParameters())
            ->setEid($parameters['eID'])
            ->setControllerActionName($this->resolveActionName($parameters))
            ->setUploadedFiles($files);

        foreach ($parameters as $argumentName => $argumentValue) {
            $eidAttribute->setArgument($argumentName, $argumentValue);
        }

        return new EidRequest(
            $mainRequest->withAttribute('eid', $eidAttribute)
        );
    }

    /**
     * @param array|\TYPO3\CMS\Core\Http\UploadedFile $files
     * @param array $parameters
     *
     * @return array
     */
    protected function mapUploadedFilesToParameters(array|UploadedFile $files, array $parameters): array {
        if (is_array($files)) {
            foreach ($files as $key => $file) {
                if (is_array($file)) {
                    $parameters[$key] = $this->mapUploadedFilesToParameters($file, $parameters[$key] ?? []);
                } else {
                    $parameters[$key] = $this->mapUploadedFileToParameters($file);
                }
            }
        } else {
            $parameters = $this->mapUploadedFileToParameters($files);
        }
        return $parameters;
    }

    /**
     * @param \TYPO3\CMS\Core\Http\UploadedFile $uploadedFile
     *
     * @return array
     */
    protected function mapUploadedFileToParameters(UploadedFile $uploadedFile): array {
        $parameters = [
            'name' => $uploadedFile->getClientFilename(),
            'type' => $uploadedFile->getClientMediaType(),
            'error' => $uploadedFile->getError(),
            'tmp_name' => $uploadedFile->getTemporaryFileName(),
        ];

        if ($uploadedFile->getSize() > 0) {
            $parameters['size'] = $uploadedFile->getSize();
        }

        return $parameters;
    }

    /**
     * @param array $parameters
     *
     * @return string
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\InvalidActionNameException
     */
    protected function resolveActionName(array $parameters): string {
        if (!isset($parameters['action']) || $parameters['action'] === '') {
            throw new InvalidActionNameException('The action is required.', 1726118825);
        }

        return $parameters['action'];
    }
}