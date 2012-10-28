<?php
/**
 * AlbumService.php
 *
 * LICENSE: Copyright David White [monkeyphp] <git@monkeyphp.com> http://www.monkeyphp.com/
 *
 * PHP Version 5.3.6
 *
 * @category   AlbumService
 * @package    AlbumService
 * @subpackage Service
 * @author     David White [monkeyphp] <git@monkeyphp.com>
 * @copyright  2011 David White (c) monkeyphp.com
 * @license    http://www.monkeyphp.com/ MonkeyPHP
 * @version    Revision: ##VERSION##
 * @link       http://www.monkeyphp.com/ MonkeyPHP
 * @created    28-Oct-2012 11:21:44
 */
namespace AlbumService\Service;
// use AlbumService
use AlbumService\Model\AlbumTable;
/**
 * AlbumService
 *
 * Service class providing SOAP endpoints for AlbumService module.
 *
 * This class provides a very very simple service interface to allow record shops
 * to check the Albums available and purchase them from an imaginary supplier.
 * This class does include logic which we would normally put into the domain/model
 * layer so dont do this kind of thing in production - but since its an
 * example I'm going to do the nasty anyway.
 * I hope that I have commented this class enough so that it is clear what it
 * going on.
 * If you have any questions or pointers, please feel free to contact me by
 * the usual channels.
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
class AlbumService
{

    /**
     * An instance of AlbumService\Model\AlbumTable
     *
     * The instance of AlbumTable that we are using is heavily based
     * on the example provided in the ZF2 Tutorial by @akrabat
     * {@link http://framework.zend.com/manual/2.0/en/user-guide/overview.html}
     *
     * @access public
     * @var    AlbumService\Model\AlbumTable
     */
    protected $albumTable;

    /**
     * Constructor with dependencies injected
     *
     * @param \AlbumService\Service\AlbumTable $albumTable The AlbumTable instance
     *
     * @access public
     * @return void
     */
    public function __construct(AlbumTable $albumTable)
    {
        $this->albumTable = $albumTable;
    }

    /**
     * Retrieve a list of all the Albums available and their current availability
     *
     * @internal Return an array of instances of AlbumService\Model\Album
     *
     * @access public
     * @return AlbumService\Model\Album[]
     */
    public function fetchAlbums()
    {
        // container array
        $albums = array();
        // loop through all of the available Albums from the database and
        // add them to the container
        foreach ($this->albumTable->fetchAll() as $album) {
            $albums[] = $album;
        }
        // return the container of Albums
        return $albums;
    }

    /**
     * Return an Album based on the supplied id parameter
     * 
     * @param int $id The Id of the Album to return
     *
     * @access public
     * @throws \Exception
     * @return AlbumService\Model\Album
     */
    public function findAlbum($id)
    {
        // attempt to locate the requested Album, if we cannot locate, then
        // throw an Exception
        if (null == ($album = $this->albumTable->getAlbum($id))) {
            throw new \Exception('We could not find the requested Album');
        }
        return $album;
    }

    /**
     * Purchase an Album by supplying the id of the Album
     *
     * @param string $albumId The id of the Album
     *
     * @access public
     * @throws \Exception
     * @return boolean True iF Album purchased successfully, False otherwise
     */
    public function purchaseAlbum($albumId)
    {

        // attempt to locate the Album based on the supplied id paramter
        // if we cannot locate the Album throw an Exception
        if (null == ($album = $this->albumTable->getAlbum($albumId))) {
            throw new \Exception('We could not find the requested Album');
        }

        // if we retrieve the Album, we need to check its stock level
        // if we dont have any available throw an Exception
        if ($album->quantity < 1) {
            throw new \Exception('It appears that we don\'t have that Album in stock at them moment');
        }

        // we have the Album in stock, so lets reduce its inventory by 1
        $album->quantity--;

        // now lets save the Album
        try {
            $this->albumTable->saveAlbum($album);
            return true;
        } catch(\Exception $exception) {
            return false;
        }
    }

}