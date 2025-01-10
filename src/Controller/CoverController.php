<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\ComicBook;
use Imagine\Image\Box;
use Imagine\Image\ManipulatorInterface;
use Imagine\Imagick\Imagine;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CoverController extends AbstractController
{
    private const CACHE_EXPIRES_AFTER = '+1 months';
    private const DEFAULT_THUMBNAIL_SIZE = 512;

    public function __construct(
        #[Autowire('%kernel.project_dir%/var/data')]
        private readonly string $dataDir,
        private readonly LoggerInterface $logger,
    ) {
    }

    #[Route('/cover', name: 'app_cover_thumbnail')]
    public function thumbnail(
        #[Autowire('%env(resolve:APP_MEDIA_DIRECTORY)%')] string $mangaRoot,
        Request $request,
        ComicBook $comicBook,
    ): Response {
        $filename = (string) $request->query->get('filename');
        $filename = rawurldecode($filename);
        $size = $request->query->getInt('size', self::DEFAULT_THUMBNAIL_SIZE);
        $realpath = $mangaRoot.'/'.$filename;
        $hash = hash('xxh128', $realpath);

        $image = $this->generateThumbnail($realpath, $comicBook, $size);
        $this->storeThumbnail($hash, $image);

        $response = new Response($image, headers: ['Content-Type' => 'image/png']);
        $response->setExpires(new \DateTimeImmutable(self::CACHE_EXPIRES_AFTER));

        return $response;
    }

    private function storeThumbnail(string $filename, string $content): void
    {
        $filename = strtolower($filename);
        $destinationDir = $this->dataDir.'/thumbnail/'.substr($filename, 0, 2).'/'.substr($filename, 2, 2).'/';
        $destinationFile = $destinationDir.basename($filename).'.png';

        // Create the destination directory if it doesn't exist
        if (!is_dir($destinationDir)) {
            mkdir($destinationDir, 0755, true);
        }
        file_put_contents($destinationFile, $content);
    }

    private function getThumbnail(string $filenameHash): string|false
    {
        // return existing thumbnail
        $filename = strtolower($filenameHash);
        $destinationDir = $this->dataDir.'/thumbnail/'.substr($filename, 0, 2).'/'.substr($filename, 2, 2).'/';
        $destinationFile = $destinationDir.basename($filename).'.png';

        if (!is_file($destinationFile)) {
            return false;
        }

        return file_get_contents($destinationFile);
    }

    private function generateThumbnail(string $realpath, ComicBook $comicBook, int $size = self::DEFAULT_THUMBNAIL_SIZE): string
    {
        if (!is_file($realpath)) {
            throw new \RuntimeException('File doesn\'t exists.');
        }

        $filenameHash = hash('xxh128', $realpath);
        $existingThumnbail = $this->getThumbnail($filenameHash);
        if (false !== $existingThumnbail) {
            $this->logger->info('Generated thumbnail already exist. Skip generating process.');

            return $existingThumnbail;
        }

        $zipArchive = new \ZipArchive();
        $entryName = $comicBook->getCover($realpath);
        if (!is_string($entryName)) {
            throw new \InvalidArgumentException(sprintf('Cannot retrieve cover from "%s".', $realpath));
        }
        $zipArchive->open($realpath);
        /** @var resource $stream */
        $stream = $zipArchive->getStream($entryName);

        $imagine = new Imagine();
        $size = new Box($size, $size);
        $image = $imagine->read($stream);

        // Crop longstrip image
        $imageSize = $image->getSize();
        $aspectRatio = $imageSize->getHeight() / $imageSize->getWidth();
        $mode = $aspectRatio > 2 ? ManipulatorInterface::THUMBNAIL_OUTBOUND : ManipulatorInterface::THUMBNAIL_INSET;

        return $image
            ->thumbnail($size, $mode)
            ->get('png');
    }
}
