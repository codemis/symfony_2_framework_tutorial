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

namespace Simplex;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * A framework for running a web server built on Symfony
 *
 * @package default
 * @author Johnathan Pulos <johnathan@missionaldigerati.org>
 **/
class Framework implements HttpKernelInterface
{
  protected $matcher;
  protected $resolver;
  protected $dispatcher;

  public function __construct(EventDispatcher $dispatcher, UrlMatcherInterface $matcher, ControllerResolverInterface $resolver)
  {
    $this->matcher = $matcher;
    $this->resolver = $resolver;
    $this->dispatcher = $dispatcher;
  }

  public function handle(Request $request, $type = HttpKernelInterface::MASTER_REQUEST, $catch = true)
  {
    try {
      $request->attributes->add($this->matcher->match($request->getPathInfo()));

      $controller = $this->resolver->getController($request);
      $arguments = $this->resolver->getArguments($request, $controller);

      $response = call_user_func_array($controller, $arguments);
    } catch (ResourceNotFoundException $e) {
      $response = new Response('Not Found', 404);
    } catch (\Exception $e) {
      $response = new Response('An error has occurred!', 500);
    }

    $this->dispatcher->dispatch('response', new ResponseEvent($response, $request));

    return $response;
  }
} // END class Framework
