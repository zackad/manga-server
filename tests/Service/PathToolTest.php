<?php

namespace App\Tests\Service;

use App\Service\PathTool;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RequestStack;

class PathToolTest extends TestCase
{
    /*
     * This test is for maintaining code coverage
     */
    public function testGetUriWhenRequestStackIsEmpty()
    {
        $requestStack = new RequestStack();
        $mangaRoot = $_ENV['MANGA_ROOT_DIRECTORY'];
        $pathTool = new PathTool($requestStack, $mangaRoot);

        $this->assertEquals('/', $pathTool->getUri());
    }
}
