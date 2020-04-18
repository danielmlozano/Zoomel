<?php
namespace Danielmlozano\Zoomel;

class ZoomObject
{

    /**
     * Set object properties from a JSON object
     *
     * @param string $json_object
     * @return void
     */
    protected function fromJson(string $json_object){
        $this->fromArray(json_decode($json_object));
    }

    /**
     * Set object properties from an array
     *
     * @param array $data
     * @return void
     */
    protected function fromArray(array $data){
        foreach($data as $k => $v){
            if(property_exists($this,$k)){
                $this->{$k} = $v;
            }
        }
    }

    /**
     * Returns an array of the instance
     *
     * @return array
     */
    public function toArray(){
        return json_decode(json_encode($this),true);
    }

}
