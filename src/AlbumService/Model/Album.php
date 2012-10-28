<?php
/**
 * Album.php
 *
 * LICENSE: Copyright David White [monkeyphp] <git@monkeyphp.com> http://www.monkeyphp.com/
 *
 * PHP Version 5.3.6
 *
 * @category   AlbumService
 * @package    AlbumService
 * @subpackage Model
 * @author     David White [monkeyphp] <git@monkeyphp.com>
 * @copyright  2011 David White (c) monkeyphp.com
 * @license    http://www.monkeyphp.com/ MonkeyPHP
 * @version    Revision: ##VERSION##
 * @link       http://www.monkeyphp.com/ MonkeyPHP
 * @created    28-Oct-2012 11:21:44
 */
namespace AlbumService\Model;
/**
 * Album
 *
 * This class represents a music Album.
 *
 * This class is based on the Album class provided in the ZF2 tutorial with
 * the addition of an quantity property
 *
 * @category   AlbumService
 * @package    AlbumService
 * @subpackage Model
 * @author     David White [monkeyphp] <git@monkeyphp.com>
 * @copyright  2011 David White (c) monkeyphp.com
 * @license    http://www.monkeyphp.com/ MonkeyPHP
 * @version    Revision: ##VERSION##
 * @link       http://www.monkeyphp.com/ MonkeyPHP
 * @created    28-Oct-2012 11:21:44
 */
class Album
{

    /**
     * The Id of the Album
     *
     * @access public
     * @var    int
     */
    public $id;

    /**
     * The name of the Album
     *
     * @access public
     * @var    string
     */
    public $artist;

    /**
     * The Title of the Album
     *
     * @access public
     * @var    string
     */
    public $title;

    /**
     * The current inventory/quantity levels of the Album
     *
     * @access public
     * @var    int
     */
    public $quantity;

}