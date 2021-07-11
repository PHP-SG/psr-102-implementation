<?php

namespace Psg\Psr102;


use Psg\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psg\Http\Server\{FrontwareInterface, LayeredAppInterface};


class Frontware implements FrontwareInterface {
	/*
	< options >:
		exit: < whether to cause a bypass >
	*/
	public function __construct($options=[]){
		$defaults = ['exit'=>false];
		$this->options = array_merge($defaults, $options);
	}
	public function process(ServerRequestInterface $request, ResponseInterface $response, LayeredAppInterface $app){
		echo "Front\n";
		if($this->options['exit']){
			return $app->createExitResponse(200, 'Exiting at frontware')->withHeader('exit_at', 'frontware');
		}

		return [$request->withHeader('frontware', '1'), $response->withHeader('frontware', '1')];

	}
}