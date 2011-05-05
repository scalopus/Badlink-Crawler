<?php


/**
 * Description of URISetDataStructure
 *
 * @author Warun Kietduriyakul <warun@jomyut.net>
 */
class URIDataSet implements ArrayAccess {
    private $container;
    
    
    public function __construct() {
        $this->container = new ArrayObject();
    }
    public function offsetSet($offset, $value) {
        $this->container[$offset] = $value;
    }
    public function offsetExists($offset) {
        return isset($this->container[$offset]);
    }
    public function offsetUnset($offset) {
        unset($this->container[$offset]);
    }
    public function offsetGet($offset) {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }
}

?>
