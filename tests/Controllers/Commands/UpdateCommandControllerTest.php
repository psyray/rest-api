<?php declare(strict_types=1);

namespace Reconmap\Controllers\Commands;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Reconmap\Models\AuditActions\CommandAuditActions;
use Reconmap\Repositories\CommandRepository;
use Reconmap\Services\ActivityPublisherService;

class UpdateCommandControllerTest extends TestCase
{
    public function testHappyPath()
    {
        $fakeCommandId = 49;

        $mockRequest = $this->createMock(ServerRequestInterface::class);
        $mockRequest->expects($this->once())
            ->method('getBody')
            ->willReturn('{"executable_path": "nmap"}');
        $mockRequest->expects($this->once())
            ->method('getAttribute')
            ->with('userId')
            ->willReturn(9);

        $mockTaskRepository = $this->createMock(CommandRepository::class);
        $mockTaskRepository->expects($this->once())
            ->method('updateById')
            ->with($fakeCommandId, ['executable_path' => 'nmap'])
            ->willReturn(true);

        $mockPublisherService = $this->createMock(ActivityPublisherService::class);
        $mockPublisherService->expects($this->once())
            ->method('publish')
            ->with(9, CommandAuditActions::UPDATED, ['type' => 'command', 'id' => $fakeCommandId]);

        $args = ['commandId' => $fakeCommandId];

        $controller = new UpdateCommandController($mockTaskRepository, $mockPublisherService);
        $response = $controller($mockRequest, $args);
        $this->assertEquals(['success' => true], $response);
    }
}
