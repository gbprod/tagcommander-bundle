<?php

/**
* This file is part of the Meup TagCommander Bundle.
*
* (c) 1001pharmacies <http://github.com/1001pharmacies/tagcommander-bundle>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Meup\Bundle\TagcommanderBundle\DataCollector;

use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 *
 */
class DataLayerCollector extends DataCollector
{
    /**
     * @var ParameterBagInterface
     */
    protected $datalayer;

    /**
     * @param ParameterBagInterface $datalayer
     */
    public function __construct(ParameterBagInterface $datalayer)
    {
        $this->datalayer = $datalayer;
        $this->data      = array(
            'values'     => array(),
            'events'     => array(),
            'containers' => array(),
        );
    }

    /**
     * {@inheritDoc}
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data['values'] = $this->datalayer->all();
    }

    public function collectEvent($tracker_name, $event_name, $values = array())
    {
        $this->data['events'][$event_name][] = array(
            'tracker' => $tracker_name,
            'values'  => $values,
        );
    }

    public function collectContainer($name, $script, $version = null, $alternative = null)
    {
        $this->data['containers'][] = array(
            'name'        => $name,
            'script'      => $script,
            'version'     => $version,
            'alternative' => $alternative,
        );
    }

    public function getValues()
    {
        return $this->data['values'];
    }

    public function getEvents()
    {
        return $this->data['events'];
    }

    public function getContainers()
    {
        return $this->data['containers'];
    }

    public function getUniqueEventsCount()
    {
        $count = 0;
        foreach ($this->data['events'] as $events) {
            $count+= count($events);
        }
        return $count;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'datalayer';
    }
}
