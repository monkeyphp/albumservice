<?php
/**
 * AlbumTable.php
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
 * @link       http://www.monkeyphp.com/
 * @created    28-Oct-2012 13:15:13
 */
namespace AlbumService\Model;
// use Zend
use Zend\Db\TableGateway\TableGateway;
/**
 * AlbumTable
 *
 * AlbumTable based largely on the ZF2 tutorial
 *
 * @category   AlbumService
 * @package    AlbumService
 * @subpackage Model
 * @author     David White [monkeyphp] <git@monkeyphp.com>
 * @copyright  2011 David White (c) monkeyphp.com
 * @license    http://www.monkeyphp.com/ MonkeyPHP
 * @version    Release: ##VERSION##
 * @link       http://www.monkeyphp.com/
 */
class AlbumTable
{

    /**
     * Instance of TableGateway class
     *
     * @access protected
     * @var    \Zend\Db\TableGateway\TableGateway
     */
    protected $tableGateway;

    /**
     * Constructor
     *
     * Inject TableGateway dependency
     *
     * @param \Zend\Db\TableGateway\TableGateway $tableGateway The TableGateway instance
     *
     * @access public
     * @return void
     */
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    /**
     * Return a result set containing ALL albums currently in the database
     *
     * @internal Don't do this in production
     *
     * @access public
     * @return ResultSet
     */
    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    /**
     * Return an instance of AlbumService\Model\Album based on the supplied id
     * parameter
     *
     * @param int $id The id of the Album to return
     *
     * @access public
     * @throws \Exception
     * @return AlbumService\Model\Album
     */
    public function getAlbum($id)
    {
        // cast the supplied argument
        $id = (int)$id;
        // retrieve the rowset based on the supplied parameter id
        $rowset = $this->tableGateway->select(array('id' => $id));
        // get the first item in the rowset
        $row = $rowset->current();
        // if nothing throw an \Exception
        if (! $row) {
            throw new \Exception('Could not find row ' . $id);
        }
        // return the item
        return $row;
    }

    /**
     * Save an Album instance
     *
     * In this example - we will not be adding new Albums to the database
     * (you could add that feature yourself) but we will be updating the
     * quantity properties as Albums are purchased via the SOAP service
     *
     * @param \AlbumService\Model\Album $album The Album instance to update
     *
     * @access public
     * @return int
     * @throws \Exception
     */
    public function saveAlbum(Album $album)
    {
        // create a data array from the supplied Album instance
        $data = array(
            'artist'   => $album->artist,
            'title'    => $album->title,
            // our additional property - quantity
            'quantity' => $album->quantity
        );

        // get the Album id
        $id = (int)$album->id;

        // this will never get called in this Example code as we do not allow additions
        if ($id == 0) {
            return $this->tableGateway->insert($data);
        } else {
            if ($this->getAlbum($id)) {
                return $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('The Album could not be updated');
            }
        }
    }

}