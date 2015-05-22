<?php
/**
 * This file is part of Symfony 2 Tutorial Framework.
 * 
 * Symfony 2 Tutorial Framework is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * Symfony 2 Tutorial Framework is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see 
 * <http://www.gnu.org/licenses/>.
 *
 * @author Johnathan Pulos <johnathan@missionaldigerati.org>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * 
 */
$APP_DIRECTORY = dirname(__DIR__);

require_once($APP_DIRECTORY . '/src/autoload.php');

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$request = Request::createFromGlobals();
$response = new Response();

$map = array(
    '/hello'    =>  $APP_DIRECTORY . '/src/pages/hello.php',
    '/bye'      =>  $APP_DIRECTORY . '/src/pages/bye.php'
);

$path = $request->getPathInfo();

if (isset($map[$path])) {
    ob_start();
    require $map[$path];
    $response->setContent(ob_get_clean());
} else {
    $response->setStatusCode(404);
    $response->setContent('Not Found');
}

$response->send();
