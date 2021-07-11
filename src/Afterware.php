<?php

namespace Psg\Psr102;


use Psg\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psg\Http\Server\{AfterwareInterface, LayeredAppInterface};


class Afterware implements AfterwareInterface{
	public function process(ServerRequestInterface $request, ResponseInterface $response, LayeredAppInterface $app){
		echo "After\n";
	}
}
