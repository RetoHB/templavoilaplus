<?php

declare(strict_types=1);

namespace Tvp\TemplaVoilaPlus\Controller\Backend\Ajax;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tvp\TemplaVoilaPlus\Core\Http\JsonResponse;
use Tvp\TemplaVoilaPlus\Service\ProcessingService;
use Tvp\TemplaVoilaPlus\Utility\TemplaVoilaUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ContentElements extends AbstractResponse
{
    /**
     * @param ServerRequestInterface $request the current request
     * @return ResponseInterface the response with the content
     */
    public function create(ServerRequestInterface $request): ResponseInterface
    {
        /** @var ProcessingService */
        $processingService = GeneralUtility::makeInstance(ProcessingService::class);

        $parameters = $request->getParsedBody();

        /** @TODO LanguageHandling! */
        /** @TODO Should we hide every element on create as it isn't configured yet? */
        $result = $processingService->insertElement(
            $parameters['destinationPointer'] ?? '',
            $parameters['elementRow'] ?? []
        );

        if ($result) {
            return new JsonResponse([
                'uid' => $result,
                'nodeHtml' => $this->record2html('tt_content', $result),
            ]);
        } else {
            return new JsonResponse(
                [
                    'error' => $result
                ],
                400 /* Bad request */
            );
        }
    }

    /**
     * @param ServerRequestInterface $request the current request
     * @return ResponseInterface the response with the content
     */
    public function reload(ServerRequestInterface $request): ResponseInterface
    {
        $parameters = $request->getParsedBody();

        /** @TODO $parameters['table'] */
        return new JsonResponse([
            'nodeHtml' => $this->record2html($parameters['table'], (int)$parameters['uid']),
        ]);
    }

    /**
     * @param ServerRequestInterface $request the current request
     * @return ResponseInterface the response with the content
     */
    public function move(ServerRequestInterface $request): ResponseInterface
    {
        /** @var ProcessingService */
        $processingService = GeneralUtility::makeInstance(ProcessingService::class);

        $parameters = $request->getParsedBody();

        $result = $processingService->moveElement(
            $parameters['sourcePointer'] ?? '',
            $parameters['destinationPointer'] ?? ''
        );

        if ($result) {
            return new JsonResponse([$result]);
        } else {
            return new JsonResponse(
                [
                    'error' => $result
                ],
                400 /* Bad request */
            );
        }
    }

    /**
     * @param ServerRequestInterface $request the current request
     * @return ResponseInterface the response with the content
     */
    public function remove(ServerRequestInterface $request): ResponseInterface
    {
        /** @var ProcessingService */
        $processingService = GeneralUtility::makeInstance(ProcessingService::class);

        $parameters = $request->getParsedBody();

        $result = $processingService->deleteElement(
            $parameters['sourcePointer'] ?? ''
        );

        return new JsonResponse([$result]);
    }
}
