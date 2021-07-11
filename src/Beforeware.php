<?php

namespace Psg\Psr102;

use Psg\Http\Message\{ServerRequestInterface};
use Psg\Http\Server\{BeforewareInterface, LayeredAppInterface};


class Beforeware implements BeforewareInterface{
	public function process(ServerRequestInterface $request, LayeredAppInterface $app){
		echo "Before\n";
	}
}