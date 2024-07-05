<?php 
namespace DeskFlow\Interfaces;
interface MetadataInterface {
    
    public function __construct($name, $description, $version){
        $this->name = $name;
        $this->description = $description;
        $this->version = $version;
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