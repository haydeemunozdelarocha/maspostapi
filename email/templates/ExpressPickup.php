<?php
namespace MaspostAPI\Template;

class ExpressPickup {
 public $body;
 private $date;
    public $translations;

 function __construct($data) {
     date_default_timezone_set('America/Denver');
     $this->date = date("Y-m-d", strtotime($data['date'] . ' ' . $data['time']));
     ExpressPickup::setBody($data['pmb']);
 }

 function setBody($pmb) {
     $this->body = file_get_contents(__DIR__.'/foundations/entrega_express.html');
     $this->body = str_replace('{PMB}', $pmb, $this->body);
     $this->body = str_replace('{DATE}', $this->date, $this->body);
 }

 function getBody() {
   return $this->body;
 }
}

