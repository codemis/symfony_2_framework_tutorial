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

namespace Simplex\Tests;

use Simplex\Framework;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;

/**
 * Testing for Framework class
 *
 * @package default
 * @author Johnathan Pulos <johnathan@missionaldigerati.org>
 **/
class FrameworkTest extends \PHPUnit_Framework_TestCase
{
  public function testNotFoundHandling()
  {
    $framework = $this->getFrameworkForException(new ResourceNotFoundException());

    $response = $framework->handle(new Request());

    $this->assertEquals(404, $response->getStatusCode());
  }

  public function testErrorHandling()
  {
    $framework = $this->getFrameworkForException(new \RuntimeException());

    $response = $framework->handle(new Request());

    $this->assertEquals(500, $response->getStatusCode());
  }

  public function testControllerResponse()
  {
    $matcher = $this->getMock('Symfony\Component\Routing\Matcher\UrlMatcherInterface');
    $matcher
        ->expects($this->once())
        ->method('match')
        ->will($this->returnValue(array(
            '_route'        =>  'foo',
            'name'          =>  'Fabien',
            '_controller'   =>  function($name) {
                return new Response('Hello ' . $name);
            }
        )));

    $resolver = new ControllerResolver();

    $framework = new Framework($matcher, $resolver);

    $response = $framework->handle(new Request());

    $this->assertEquals(200, $response->getStatusCode());
    $this->assertContains('Hello Fabien', $response->getContent());
  }

  protected function getFrameworkForException($exception)
  {
    $matcher = $this->getMock('Symfony\Component\Routing\Matcher\UrlMatcherInterface');

    $matcher
      ->expects($this->once())
      ->method('match')
      ->will($this->throwException($exception));

    $resolver = $this->getMock('Symfony\Component\HttpKernel\Controller\ControllerResolverInterface');

    return new Framework($matcher, $resolver);
  }
} // END class FrameworkTest extends \PHPUnit_Framework_TestCase
