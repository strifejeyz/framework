<?php namespace Kernel\Requests;

/**
 * Interface FileRequestInterface
 * @package Kernel
 */
interface FileRequestInterface
{
    public function __construct($request);

    public function originalName();

    public function tmpName();

    public function size();

    public function save($location);

    public function getExtension();

    public function getFilename();

    public function uniqueName();
}

/**
 * Handles $_FILES request
 * and provides various methods to get
 * each key values from $_FILES array.
 *
 * Class FileRequest
 * @package Kernel
 **/
class FileRequest implements FileRequestInterface
{
    public $request;

    /**
     * get the request
     *
     * @param $request
     */
    public function __construct($request)
    {
        return $this->request = $request;
    }


    /**
     * Returns the original filename
     *
     * @return mixed
     */
    public function originalName()
    {
        return $this->request['name'];
    }


    /**
     * Returns temporary location of the file
     * on the server.
     *
     * @return mixed
     */
    public function tmpName()
    {
        return $this->request['tmp_name'];
    }


    /**
     * Returns file size in bytes
     *
     * @return mixed
     */
    public function size()
    {
        return $this->request['size'];
    }


    /**
     * Return file mime type
     *
     * @return mixed
     */
    public function mimeType()
    {
        return $this->request['type'];
    }


    /**
     * Save the file to a location specified
     * at param $location from a param $filename
     *
     * @param $location
     * @return bool
     */
    public function save($location)
    {
        if (move_uploaded_file($this->request['tmp_name'], $location)) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * Returns a file extension
     *
     * @return mixed
     */
    public function getExtension()
    {
        return pathinfo($this->originalName(), PATHINFO_EXTENSION);
    }


    /**
     * Returns a file name without
     * the file extension
     *
     * @return string
     */
    public function getFilename()
    {
        return pathinfo($this->originalName(), PATHINFO_FILENAME);
    }


    /**
     * Returns a unique file name
     *
     * @return string
     */
    public function uniqueName()
    {
        return md5(time()) . '.' . $this->getExtension();
    }

}