<?php 
namespace DeskFlow\Utils\Support;
use DeskFlow\Interfaces\MetadataInterface;
class ServiceMetadata implements MetadataInterface{
    public $name;
    public $description;
    public $version;
    public $author;
    public function __construct($params){
        $this->name = isset($params['name']) ? $params['name'] : '';
        $this->description = isset($params['description'])? $params['description'] : '';
        $this->version = isset($params['version'])? $params['version'] : '';
        $this->author = isset($params['author'])? $params['author'] : '';
    }
    
    public function setName($name): void {
        $this->name = $name;
    }

    public function setDescription($description): void {
        $this->description = $description;
    }
    
    public function setVersion($version): void {
        $this->version = $version;
    }
    
    public function setAuthor($author): void {
        $this->author = $author;
    }

    public function getName(): String{
        return '';
    }

    public function getDescription(): String{
        return '';
    }

    public function getVersion(): String{
        return '';
    }

    public function getAuthor(): String {
        return '';
    }
}