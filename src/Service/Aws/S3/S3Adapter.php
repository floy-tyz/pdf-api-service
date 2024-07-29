<?php

namespace App\Service\Aws\S3;

use App\Request\Exception\BusinessException;
use Aws\Exception\AwsException;
use Aws\S3\S3Client;
use Aws\S3\S3ClientInterface;
use Exception;
use GuzzleHttp\Psr7\Stream;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class S3Adapter implements S3AdapterInterface
{
    private S3ClientInterface $s3Client;

    public function __construct(string $endpoint, string $region, string $version, array $credentials)
    {
        $this->s3Client = new S3Client([
            'endpoint' => $endpoint,
            'region' => $region,
            'version' => $version,
            'use_path_style_endpoint' => true,
            'credentials' => $credentials,
        ]);
    }

    public function putObject(string $bucketName, string $key, string $filePath): string
    {
        if (!$this->isBucketExists($bucketName)) {
            $this->createBucket($bucketName);
        }

        $result = $this->s3Client->putObject([
            'Bucket' => $bucketName,
            'Key' => $key,
            'SourceFile' => $filePath,
        ]);

        return $result->get('ObjectURL');
    }

    public function getObjectContent(string $bucketName, string $key): string
    {
        if (!$this->isBucketExists($bucketName)) {
            throw new BusinessException('Пространство имен файла не найдено');
        }

        $result = $this->s3Client->getObject([
            'Bucket' => $bucketName,
            'Key' => $key,
        ]);

        /** @var Stream $body */
        $body = $result->get('Body');

        return $body->getContents();
    }

    /**
     * @throws Exception
     */
    public function getObjectHead(string $bucketName, string $key): array
    {
        try {
            if (!$this->isBucketExists($bucketName)) {
                throw new Exception("Bucket not found");
            }

            $result = $this->s3Client->headObject([
                'Bucket' => $bucketName,
                'Key' => $key,
            ]);

            return [
                'mime_type' => $result->get('ContentType'),
                'size' => $result->get('ContentLength'),
            ];
        } catch (AwsException) {
            throw new NotFoundHttpException();
        }
    }

    public function isBucketExists(string $bucketName): bool
    {
        try {
            return $this->s3Client->doesBucketExistV2($bucketName);
        } catch (Exception $e) {
            throw new BusinessException($e->getMessage());
        }
    }

    public function createBucket(string $bucketName): bool
    {
        try {
            $this->s3Client->createBucket([
                'ACL' => 'public-read',
                'Bucket' => $bucketName,
            ]);

            return true;
        } catch (Exception $e) {
            throw new BusinessException($e->getMessage());
        }
    }
}