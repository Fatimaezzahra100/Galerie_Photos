<?php

$config = [
  'default_controller' => 'Gallery',
  'default_method' => 'index',
  'core_classes'=> ['Controller', 'Loader', 'Model'],
  'models'=>['Gallery']
];

foreach ($config['core_classes'] as $classname) require "/galerie/core/$classname";

//function generate_error_404() { header("HTTP/1.0 404 Not Found"); exit; }

function generate_error_404($exception) {
  header("HTTP/1.0 404 Not Found");
  include '/galerie/views/header.php';
  echo $exception->getMessage();
  include '/galerie/views/footer.php';
}

function get_path_elements() {
  if (!isset($_SERVER['PATH_INFO'])) { return null; }
  $path_info = $_SERVER['PATH_INFO'];
  $path_elements = explode('/', $path_info);
  array_shift($path_elements);
  return $path_elements; 
}

function path_contains_controller_name($path_elements) {
  return $path_elements!==null && count($path_elements)>=1; 
}

function path_contains_method_name($path_elements) {
  return $path_elements!==null && count($path_elements)>=2;
}

function get_controller_name($path_elements) {
  global $config;
  return (path_contains_controller_name($path_elements))
      ?  $path_elements[0] 
      : $config['default_controller'];
}

function get_parameters($path_elements) {
  if (empty($path_elements) || count($path_elements)<=2) { return []; }
  return array_slice($path_elements, 2);
}

function get_method_name($path_elements) {
  global $config;
  return (path_contains_method_name($path_elements))
      ?  $path_elements[1] 
      : $config['default_method'];
}

function create_controller($controller_name) {
  $controller_classname = ucfirst(strtolower($controller_name));
  $controller_filename = '/galerie/controllers/'.$controller_classname.'.php';
  if (!file_exists($controller_filename)) { throw new Exception(" Erorr DNS ! file name !"); }
  include $controller_filename;
  if (!class_exists($controller_classname)) { throw new Exception(" Erorr DNS ! Class_name does not exist !"); }
  return new $controller_classname();
}

function call_controller_method($controller, $method_name, $parameters) {
 $reflectionObject = new ReflectionObject($controller);
  if (! $reflectionObject->hasMethod($method_name)) { throw new Exception(" Erorr, DNS ! Method does not exist!"); }
  $reflectionMethod = $reflectionObject->getMethod($method_name);
  if ($reflectionMethod->getNumberOfParameters()!=count($parameters)){ throw new Exception(" Erorr, parameter does not exist ! ! "); }
  $reflectionMethod->invokeArgs($controller, $parameters);
}

try {
  $path_elements = get_path_elements();
  $controller_name = get_controller_name($path_elements);
  $method_name = get_method_name($path_elements);
  $controller = create_controller($controller_name);
  $parameters = get_parameters($path_elements);
  call_controller_method($controller, $method_name, $parameters);
} catch (Exception $exception) {
  generate_error_404($exception);
}


/*
$path_elements = get_path_elements();
$controller_name = get_controller_name($path_elements);
$method_name = get_method_name($path_elements);
$controller = create_controller($controller_name);
$parameters = get_parameters($path_elements);

echo 'path_elements : '; var_dump($path_elements); echo '<br>';
echo 'controller_name : '; var_dump($controller_name);echo '<br>';
echo 'method_name : '; var_dump($method_name);echo '<br>';
echo 'controller : '; var_dump($controller);echo '<br>';
echo 'parameters : '; var_dump($parameters);echo '<br>';

call_controller_method($controller, $method_name, $parameters);   */
