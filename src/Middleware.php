<?php

namespace Psg\Psr102;


use Psg\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psg\Http\Server\{MiddlewareInterface, MiddlewareAppInterface};


class Middleware implements MiddlewareInterface {
	static public $i = 1;
	/*
	< options >:
		exit: < whether to cause a bypass >
		id: < id of middleware >
	*/
	public function __construct($options=[]){
		$defaults = ['exit'=>false, 'id' => self::$i++];
		$this->options = array_merge($defaults, $options);
		$this->id = $this->options['id'];
	}
	public function process(ServerRequestInterface $request, MiddlewareAppInterface $app): ResponseInterface {
		echo "Middle\n";
		if($this->options['exit']){
			return $app->createResponse(200, 'exited in middleware')->withHeader('exit_at', 'middleware id '.$this->id);
		}
		echo "wrap$this->id{\n";
		$response = $app($request->withAddedHeader('middleware', $this->id));
		echo "}wrap$this->id\n";
		return $response->withAddedHeader('middleware', $this->id);
	}
}