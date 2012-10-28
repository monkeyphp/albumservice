<?php
/**
 * module.config.php
 *
 * LICENSE: Copyright David White [monkeyphp] <david@monkeyphp.com> http://www.monkeyphp.com/
 *
 * PHP Version 5.3.6
 *
 * @category   AlbumService
 * @package    AlbumService
 * @author     David White [monkeyphp] <david@monkeyphp.com>
 * @copyright  2011 David White (c) monkeyphp.com
 * @license    http://www.monkeyphp.com/
 * @version    Revision: ##VERSION##
 * @link       http://www.monkeyphp.com/
 * @since
 * @created    28-Oct-2012 11:16:01
 */
/**
 * @category   AlbumService
 * @package    AlbumService
 * @author     David White [monkeyphp] <david@monkeyphp.com>
 * @copyright  2011 David White (c) monkeyphp.com
 * @license    http://www.monkeyphp.com/
 * @version    Release: ##VERSION##
 * @link       http://www.monkeyphp.com/
 * @since
 */
return array(
    // routes
    'router' => array(
        'routes' => array(
            // out main route providing access to the Soap Service
            'service' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/album-service/service'
                )
            )
        )
    )
);