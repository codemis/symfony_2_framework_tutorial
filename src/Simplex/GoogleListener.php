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

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Add the Google Code to the page
 *
 * @package default
 * @author Johnathan Pulos <johnathan@missionaldigerati.org>
 **/
class GoogleListener implements EventSubscriberInterface
{

  public static function getSubscribedEvents()
  {
    return array('response' =>  'onResponse');
  }

  public function onResponse(ResponseEvent $event)
  {
    $response = $event->getResponse();
    $headers = $response->headers;

    if ($response->isRedirection()
        || ($headers->has('Content-Type') && false === strpos($headers->get('Content-Type'), 'html'))
        || 'html' !== $event->getRequest()->getRequestFormat()
    ) {
        return;
    }

    $response->setContent($response->getContent() . ' GA CODE');
  }

} // END class GoogleListener
