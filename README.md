# PSR 102 Implementation

The intent of this implementation is to give a well documented prototype of how LayeredApp can be implemented.  The complexity
of integrating normal middleware with frontware and backware has, to my knowledge, prevented this from ever being done, which
makes the example all the more necessary.

## Notes
In my implementation, I provide the callable with the parameters ($request, $response, $app).  It is, however, expectable that frameworks will want to:
-	inject parameters as necessary into the core call
-	inject parameters into the __construct if the core is an instantiable
As such, the parameters are left up to the framework.

### Possible Improvements
-	just in time middleware construction with dependency injection to __construct
-	add exception if a ware is added after it's stage has passed

## Use

To see all the layers, which are set to echo to announce themselves and each add a header
```php
use Psg\Psr102\{Beforeware, Frontware, Middleware, Backware, Afterware, LayeredApp};

$core = function($request, $response, $app){
	echo "Request Headers:\n";
	var_export($request->getHeaders());
	echo "\nadding body\n";
	return $response->withBodyString("\nBODY\n");
};

$App = new LayeredApp;
$App->add(new Beforeware);
$App->add(new Frontware);
$App->add(new Middleware);
$App->add(new Middleware);
$App->add(new Backware);
$App->add(new Afterware);
$App->core($core);
/*>
Before
Front
Middle
wrap1{
Middle
wrap2{
Request Headers:
array (
  'Host' =>
  array (
    0 => 'bobery.com',
  ),
  'frontware' =>
  array (
    0 => '1',
  ),
  'middleware' =>
  array (
    0 => '1',
    1 => '2',
  ),
)
adding body
}wrap2
}wrap1
Back

==========RESPONSE {=============
array (
  'middleware' =>
  array (
    0 => '2',
    1 => '1',
  ),
  'Backware' =>
  array (
    0 => '1',
  ),
)
BODY

==========} RESPONSE=============
After
*/
```

To see the ability to exit at certain phases
```php
# Exit in frontware
$App = new LayeredApp;
$App->add(new Beforeware);
$App->add(new Frontware(['exit'=>true]));
$App->add(new Middleware);
$App->add(new Middleware);
$App->add(new Backware);
$App->add(new Afterware);
$App->core($core);
/*>
Before
Front
Back

==========RESPONSE {=============
array (
  'exit_at' =>
  array (
    0 => 'frontware',
  ),
  'Backware' =>
  array (
    0 => '1',
  ),
)
==========} RESPONSE=============
After
*/

# Exit in middleware
$App = new LayeredApp;
$App->add(new Beforeware);
$App->add(new Frontware);
$App->add(new Middleware(['exit'=>true]));
$App->add(new Middleware);
$App->add(new Backware);
$App->add(new Afterware);
$App->core($core);

/*>
Before
Front
Middle
Back

==========RESPONSE {=============
array (
  'exit_at' =>
  array (
    0 => 'middleware id 1',
  ),
  'Backware' =>
  array (
    0 => '1',
  ),
)
==========} RESPONSE=============
After
*/

```



