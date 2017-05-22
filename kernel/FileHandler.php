<?php namespace Kernel;

/**
 * Handles File read, write, permissions
 * and any other stuff for file management.
 *
 * Class FileHandler
 *
 * @package Kernel
 */
class FileHandler
{
    /**
     * Location of a file as string
     */
    private $filename = null;

    /**
     * Holds resource of an opened file.
     */
    private $handle = null;


    /**
     * FileHandler constructor.
     *
     * @param $mode
     * @param $filename
     */
    public function __construct($filename, $mode = 'r')
    {
        if (is_null($this->filename)) {
            $this->filename = $filename;
        }
        $this->handle = fopen($this->filename, $mode);
        return ($this->handle);
    }


    /**
     * Return file handle instance
     *
     * @return resource
     */
    public function handle()
    {
        return (!is_null($this->handle) ? $this->handle : false);
    }


    /**
     * Open a file handle
     *
     * @param $mode
     * @return resource
     */
    public function open($mode)
    {
        $this->handle = fopen($this->filename, $mode);
        return (true);
    }


    /**
     * return base filename
     *
     * @return mixed
     */
    public function filename()
    {
        return pathinfo($this->filename, PATHINFO_FILENAME);
    }


    /**
     * return base directory
     *
     * @return mixed
     */
    public function dirname()
    {
        return pathinfo($this->filename, PATHINFO_DIRNAME);
    }


    /**
     * return file extension
     *
     * @return mixed
     */
    public function extension()
    {
        return pathinfo($this->filename, PATHINFO_EXTENSION);
    }



    /**
     * copy a file to somewhere
     *
     * @param $destination
     * @return mixed
     */
    public function copy($destination)
    {
        return copy($this->filename, $destination);
    }


    /**
     * Open a file
     *
     * @param null $size
     * @return resource
     */
    public function read($size = null)
    {
        $this->open('r');
        if (!is_null($size)) {
            $resource = fread($this->handle, $size);
        } else {
            $resource = fread($this->handle, filesize($this->filename));
        }
        $this->close();

        return ($resource);
    }


    /**
     * Write content to a file
     *
     * @param $message
     * @param $mode
     * @param null $length
     * @return resource
     */
    public function write($message, $mode = 'a', $length = null)
    {
        $this->open($mode);
        if (!is_null($length)) {
            $result = fwrite($this->handle, $message, $length);
        } else {
            $result = fwrite($this->handle, $message);
        }
        $this->close();
        return ($result);
    }


    /**
     * Change permissions to a file
     *
     * @param $code
     * @return bool
     */
    public function changePerms($code)
    {
        return (chmod($this->filename, $code));
    }


    /**
     * Change owner of a file
     *
     * @param $nameOrId
     * @return bool
     */
    public function changeOwner($nameOrId)
    {
        return (chown($this->filename, $nameOrId));
    }


    /**
     * Delete a file
     *
     * @return bool
     */
    public function delete()
    {
        return (unlink($this->handle));
    }


    /**
     * Read CSV per line and converts it
     * to array.
     *
     * @return bool
     */
    public function firstCsv()
    {
        $this->open('r');
        $resource = fgetcsv($this->handle);
        $this->close();
        return ($resource);
    }


    /**
     * Read the very first line
     *
     * @return bool
     */
    public function firstLine()
    {
        $this->open('r');
        $resource = fgets($this->handle);
        $this->close();
        return ($resource);
    }


    /**
     * Read the very first character on first line
     *
     * @return bool
     */
    public function firstChar()
    {
        $this->open('r');
        $resource = fgetc($this->handle);
        $this->close();
        return ($resource);
    }


    /**
     * Read all lines and set to array
     *
     * @return bool
     */
    public function toArray()
    {
        $this->open('r');
        $collection = array();
        while (!feof($this->handle)) {
            array_push($collection, fgets($this->handle));
        }
        $this->close();
        return ($collection);
    }


    /**
     * Read all lines as CSV and set to array
     *
     * @return bool
     */
    public function toCsv()
    {
        $this->open('r');
        $collection = array();
        while (!feof($this->handle)) {
            array_push($collection, fgetcsv($this->handle));
        }
        $this->close();
        return ($collection);
    }


    /**
     * Return the timestamp of the last access
     * made to the file.
     *
     * @return mixed
     */
    public function lastAccessTime()
    {
        return (fileatime($this->filename));
    }


    /**
     * Return the timestamp of time when
     * file was last changed
     *
     * @return mixed
     */
    public function lastChanged()
    {
        return (filectime($this->filename));
    }


    /**
     * Returns the owner User ID(owner)
     * of a file
     *
     * @return mixed
     */
    public function owner()
    {
        return (fileowner($this->filename));
    }


    /**
     * Returns the size of a file
     *
     * @return mixed
     */
    public function size()
    {
        return (filesize($this->filename));
    }


    /**
     * Returns the type of a file
     *
     * @return mixed
     */
    public function type()
    {
        return (filetype($this->filename));
    }


    /**
     * Returns current permission state
     * of a file
     *
     * @return mixed
     */
    public function perms()
    {
        return (fileperms($this->filename));
    }


    /**
     * Return the timestamp of time when
     * file was last modified
     *
     * @return mixed
     */
    public function lastModified()
    {
        return (filemtime($this->filename));
    }


    /**
     * Close an open file
     *
     * @return bool
     */
    public function close()
    {
        return fclose($this->handle);
    }
}