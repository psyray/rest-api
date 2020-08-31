<?php

declare(strict_types=1);

namespace Reconmap\Controllers\Projects;

use Psr\Http\Message\ServerRequestInterface;
use Reconmap\Controllers\Controller;
use Reconmap\Repositories\ProjectUserRepository;

class AddProjectUserController extends Controller
{

	public function __invoke(ServerRequestInterface $request, array $args): array
	{
		$projectId = (int)$args['id'];
		$requestBody = json_decode((string)$request->getBody());

		$userData = $requestBody;

		$repository = new ProjectUserRepository($this->db);
		$result = $repository->create($projectId, (int)$userData->userId);

		return ['success' => $result];
	}
}
