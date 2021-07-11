<?php

namespace Psg\Psr102;


use Psg\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psg\Http\Server\{BackwareInterface, LayeredAppInterface};


class Backware implements BackwareInterface {
	public function process(ServerRequestInterface $request, ResponseInterface $response, LayeredAppInterface $app): ResponseInterface {
		echo "Back\n";
		return $response->withHeader('Backware', '1');
	}
}