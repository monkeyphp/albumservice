<?php
/**
 * Module.php
 *
 * LICENSE: Copyright David White [monkeyphp] <git@monkeyphp.com> http://www.monkeyphp.com/
 *
 * PHP Version 5.3.6
 *
 * @category  AlbumService
 * @package   AlbumService
 * @author    David White [monkeyphp] <git@monkeyphp.com>
 * @copyright 2011 David White (c) monkeyphp.com
 * @license   http://www.monkeyphp.com/ MonkeyPHP
 * @version   Revision: ##VERSION##
 * @link      http://www.monkeyphp.com/ MonkeyPHP
 * @created   28-Oct-2012 11:21:44
 */
namespace AlbumService;
// use AlbumService
use AlbumService\Service\AlbumService;
use AlbumService\Model\Album;
use AlbumService\Model\AlbumTable;
// use Zend
use Zend\Mvc\MvcEvent;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Soap\AutoDiscover;
use Zend\Soap\Server;
use Zend\Soap\Wsdl\ComplexTypeStrategy\ArrayOfTypeComplex;
/**
 * Module
 *
 * This is the Module class for our AlbumService module. Its based on the
 * Module class from the tutorial {@link http://framework.zend.com/manual/2.0/en/user-guide/overview.html}
 * with the addition of our own onBootstrap event handling (that is where the fun happens).
 *
 * In the onBootstrap we set up an event listener to capture the Application dispatch
 * event (I think that happens fairly early) to capture the requested Route.
 * If the route matches the one that our service will listen on, we check to see
 * if the Request is for our WSDL (?wsdl) or is an actual call to the Service itself.
 *
 * If the request is for the WSDL we use AutoDiscover to generate the WSDL before returning
 * it early (short circuiting).
 * If the request is to the SOAP service itself, we hand off to the service class
 * {@see AlbumService\Service\AlbumService} before again short circuiting the response.
 *
 * The AlbumService itself does not contain any views or controllers as we dont need them.
 *
 * The Models (@see AlbumService\Model\Album} and {@see AlbumService\Model\AlbumTable} are
 * based on those provided in the ZF2 tutorial courtesy of Rob Allen
 * {@link http://zf2.readthedocs.org/en/latest/index.html#userguide} {@link http://akrabat.com/}.
 *
 * I cannot claim that any code in this Module is amazing - you'll have to form your own
 * opinion; it's my first real attempt at creating a Module
 * (ask Evan how great Modules are {@link http://www.flickr.com/photos/86569608@N00/8066213497})
 * but I hope that it gives an example of how to use SOAP in ZF2.
 *
 * If you have any questions or pointers, (you can get me on the usual channels)
 * I'd be much appreciative.
 *
 * Regards MonkeyPHP
 *
 * @category   AlbumService
 * @package    AlbumService
 * @subpackage Service
 * @author     David White [monkeyphp] <git@monkeyphp.com>
 * @copyright  2011 David White (c) monkeyphp.com
 * @license    http://www.monkeyphp.com/ MonkeyPHP
 * @version    Release: ##VERSION##
 * @link       http://www.monkeyphp.com/ MonkeyPHP
 */
class Module
{

    /**
     * Return an array of configs to the ServiceManager so that our objects
     * can be created with all dependencies injected
     *
     * @access public
     * @return array
     */
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                // whenever an instance of our AlbumService is created - we need
                // to inject the AlbumTable instance into it
                'AlbumService\Service\AlbumService' => function ($serviceManager) {
                    // retrieve the AlbumTable from the ServiceManager
                    $albumTable = $serviceManager->get('AlbumService\Model\AlbumTable');
                    $service = new AlbumService($albumTable);
                    return $service;
                },
                // whenever an instance of our AlbumTable is created - we need
                // to inject in the TableGateway
                'AlbumService\Model\AlbumTable' => function ($serviceManager) {
                    $tableGateway = $serviceManager->get('AlbumServiceTableGateway');
                    $table = new AlbumTable($tableGateway);
                    return $table;
                },
                // configure and return an TableGateway instance
                // TBH - I'm not totally sure what this does ;)
                'AlbumServiceTableGateway' => function ($serviceManager) {
                    $dbAdapter = $serviceManager->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Album());
                    return new TableGateway('albums', $dbAdapter, null, $resultSetPrototype);
                },
            )
        );
    }

    /**
     * Return an array of config options for the class autoloader to that it
     * can find our classes
     *
     * @access public
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoLoader' => array(
                __DIR__ . '/autoload_classmap.php'
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__  . '/src/' . __NAMESPACE__,
                )
            )
        );
    }

    /**
     * Return module config options
     *
     * @access public
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * Hook into the MVC module bootstrap event and register our event listener
     * to capture requests to our SOAP webservice
     *
     * @param \Zend\Mvc\MvcEvent $event The MvcEvent object
     *
     * @access public
     * @return void
     */
    public function onBootstrap(MvcEvent $event)
    {
        // retrieve an instance of the SharedEventManager
        // @var SharedEventManagerInterface
        // @var SharedEventManager
        $sharedEventManager = $event->getApplication()->getEventManager()->getSharedManager();

        // attach to the Application dispatch event (I think that this is early)
        $sharedEventManager->attach('Zend\\Mvc\\Application', 'dispatch', function (MvcEvent $event) {

            // lets check the route to see if it matches one that we are interested in
            // if the route is the one that we are interested in then we'll do
            // our processing - else we'll ignore
            if ($event->getRouteMatch()->getMatchedRouteName() == 'service') {
                // event should contain a Response object
                $response = $event->getResponse();
                // event should contain a Request object
                $request = $event->getRequest();
                // event should contain a Request object so we'll use that to get
                // the current Uri
                $uri = $request->getUri();
                // SOAP locations are picky! We need to build out WSDL uri based on
                // the supplied uri - this can be a head f**k if its wrong
                $wsdl = sprintf('%s://%s%s', $uri->getScheme(), $uri->getHost(), $uri->getPath());
                // retrieve an instance of our Service class (the class that will handle the actual SOAP request)
                $service = $event->getApplication()->getServiceManager()->get('AlbumService\Service\AlbumService');
                // when a request is made to the route 'service' we need to check
                // if a request is being made for the Wsdl or an actual call is being made
                // to the SOAP service itself
                // we will use the '?wsdl' fragment to distinguish
                // so it a request is made for 'http://example.com/service?wsdl'
                // we will return the WSDL XML else we will assume that it is a
                // request to the actual SOAP service
                if (isset($request->getQuery()->wsdl)) {
                    // this is a call to request the WSDL
                    // create a new AutoDiscover instance
                    $autoDiscover = new AutoDiscover();
                    // set the Strategy - there are a variety of Strategies available
                    $autoDiscover->setComplexTypeStrategy(new ArrayOfTypeComplex());
                    // pass our $service to the AutoDiscover
                    // @todo docs seem to suggest that setClass requires a class name not an object
                    $autoDiscover->setClass($service);
                    // set the Uri
                    $autoDiscover->setUri($wsdl);
                    // WSDLs are XML documents to lets set the headers
                    $response->getHeaders()->addHeaderLine('Content-Type', 'text/xml');
                    // output the XML
                    $response->setContent($autoDiscover->toXml());

                } else {
                    // we will assume that a call is being made to the SOAP service itself
                    // create a Server instance supplying the WSDL location and
                    // a classmap so it can convert XSD types to PHP classes and back
                    $server = new Server($wsdl . '?wsdl', array('classmap' => array(
                        'Album' => 'AlbumService\Model\Album'
                    )));
                    // now we set the class that will do the handling
                    $server->setObject($service);
                    // now prep the Response object with headers
                    $response->getHeaders()->addHeaderLine('Content-Type', 'text/xml');
                    // finally set the Response content with output form the SOAP server
                    $response->setContent($server->handle());
                }
                // so we'll short circuit from here and return the Response directly
                // @todo we could set the xml headers here instead?
                return $response;
            }
        }, 100);
    }

}