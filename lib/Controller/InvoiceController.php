<?php

declare(strict_types=1);

namespace OCA\Expenses\Controller;

use OCP\AppFramework\Http\DataDownloadResponse;
use OCP\IRequest;
use OCP\IConfig;
use OCP\Files\IRootFolder;

use OCP\AppFramework\Http;
use OCP\AppFramework\Http\Attribute\NoAdminRequired;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Http\NotFoundResponse;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\Attribute\FrontpageRoute;
use OCP\AppFramework\Http\Attribute\NoCSRFRequired;
use OCP\Files\Folder;
use OCP\Files\Node;
use OCP\Files\File;
use OCP\IPreview;




class InvoiceController extends Controller {
	public const INVOICE_INBOX = 'invoice/inbox';
    
    private IConfig $config;
    private IRootFolder $rootFolder;
    private string|null $userId;

    private IPreview $previewManager;

    public function __construct(string $appName, IRequest $request, IConfig $config, IRootFolder $rootFolder, ?string $userId, IPreview $previewManager) {
        parent::__construct($appName, $request);
        $this->config = $config;
        $this->rootFolder = $rootFolder;
        $this->userId = $userId;
        $this->previewManager = $previewManager;
    }
    
    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     */
    #[NoAdminRequired]
    #[NoCSRFRequired]
    #[FrontpageRoute(verb: 'GET', url: '/api/invoice/inbox')]
    public function getInvoiceInbox(): DataResponse {
        $userFolder = $this->rootFolder->getUserFolder($this->userId);
        if ($userFolder->nodeExists(self::INVOICE_INBOX)) {
            $inboxDir = $userFolder->get(self::INVOICE_INBOX);
            if ($inboxDir instanceof Folder) {
                $nodeList = $inboxDir->getDirectoryListing();
                $files = array_filter($nodeList, static function (Node $node) {
                    return $node instanceof File;
                });
                $files_ids = array_map(static function (Node $node) {
                    return array('id' => $node->getId(), "name" => $node->getName(), "mtime" => $node->getMTime());
                }, $files);
                return new DataResponse(
                    $files_ids
                );
            } else {
                return new DataResponse(
                    array("message"=> "Selected Inbox Path is Not a Folder!"),
                    Http::STATUS_INTERNAL_SERVER_ERROR,
                );
            }
        }
        return new DataResponse(
            array("message"=> "Selected Inbox Path not Found!"),
            Http::STATUS_NOT_FOUND,
        );
    }

    #[NoAdminRequired]
    #[NoCSRFRequired]
    #[FrontpageRoute(verb: 'GET', url: '/api/invoice/pdf/{fileId}')]
    public function getInvoicePdf(int $fileId): DataDownloadResponse {
        $invoicePDF = $this->rootFolder->getFirstNodeById($fileId);
        if ($invoicePDF == null){
            return new DataDownloadResponse(
                data: "", filename: "", contentType: "application/x-empty", status: Http::STATUS_NOT_FOUND,
            );
        }
        if ($invoicePDF->isUpdateable()) {
            if ($invoicePDF instanceof File) {
                $response = new DataDownloadResponse(
                    $invoicePDF->getContent(),
                    $invoicePDF->getName(),
                    $invoicePDF->getMimeType()
                );
                $response->cacheFor(60 * 60);
                return $response;
            } else {
                return new DataDownloadResponse(
                    data: "", filename: "", contentType: "application/x-empty", status: Http::STATUS_INTERNAL_SERVER_ERROR,
                );
            }
        }
        return new DataDownloadResponse(
            data: "", filename: "", contentType: "application/x-empty", status: Http::STATUS_FORBIDDEN,
        );
    }

    #[NoAdminRequired]
    #[NoCSRFRequired]
    #[FrontpageRoute(verb: 'GET', url: '/api/invoice/pdf/{fileId}/thumbnail')]
    public function getInvoicePdfThumbnail(int $fileId): DataDownloadResponse {
        $invoicePDF = $this->rootFolder->getFirstNodeById($fileId);
        if ($invoicePDF == null){
            return new DataDownloadResponse(
                data: "", filename: "", contentType: "application/x-empty", status: Http::STATUS_NOT_FOUND,
            );
        }
        if ($invoicePDF->isUpdateable()) {
            if ($invoicePDF instanceof File) {
                $invoicePDFPreview = $this->previewManager->getPreview($invoicePDF);
                $response = new DataDownloadResponse(
                    $invoicePDFPreview->getContent(),
                    '',
                    $invoicePDFPreview->getMimeType()
                );
                $response->cacheFor(60 * 60);
                return $response;
            } else {
                return new DataDownloadResponse(
                    data: "", filename: "", contentType: "application/x-empty", status: Http::STATUS_INTERNAL_SERVER_ERROR,
                );
            }
        }
        return new DataDownloadResponse(
            data: "", filename: "", contentType: "application/x-empty", status: Http::STATUS_FORBIDDEN,
        );
    }


}