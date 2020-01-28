<?php
namespace MaspostAPI;
require_once(__DIR__.'/../../helpers/Helpers.php');

use MaspostAPI\Helpers\Date;

class ExpressPickup {
    public $body;
    private $date;
    private $pmb;
    private $table;
    private $isAdmin;
    private $ids;
    public $translations;
    private $content;

 function __construct($data, $isAdmin = false) {
     date_default_timezone_set('America/Denver');
     $this->date = $data['date'];
     $this->isAdmin = $isAdmin;
     $this->pmb = $data['pmb'];
     $this->packages = $data['packages'];
     $this->ids = $data['ids'];

     ExpressPickup::setContent();
     ExpressPickup::setTable();
     ExpressPickup::setBody();
 }

 function setBody() {
     $this->body = file_get_contents(__DIR__.'/foundations/entrega_express.html');
     $this->body = str_replace('{CONTENT}', $this->content, $this->body);
     $this->body = str_replace('{TABLE}', $this->table, $this->body);
 }

 function setContent() {
     if (Date::isWeekend($this->date)) {
         if (!$this->isAdmin) {
             $this->content = '<h3>Programada para fin de semana.</h3>
            <p>Hemos recibido tu solicitud de entrega express. Los fines de
                semana las entregas se hacen solamente con cita. Nuestro equipo confirmará
                su disponibilidad para terminar de programar esta entrega. Recibirás un correo
                con la confirmación. Gracias!</p>';
         } else {
             $ids = implode(',', $this->ids);
             $hora=strftime('%I:%M %p',strtotime($this->date));
             $fecha=strftime('%y-%m-%d',strtotime($this->date));
             $this->content = '<h3>Programada para fin de semana.</h3>
            <p>Para confirmar la disponibilidad y programar esta entrega, <a href="http://maspostwarehouseusers.com/confirmar_entrega_express?ids='.$ids.'&fecha='.$fecha . '&hora='.$hora.'"> haz click aquí.</a>';
         }
     } else {
        $this->content = '<p>Hemos recibido tu solicitud de entrega express. A continuación confirmamos los detalles de tu entrega.</p>';
     }
 }

 function setTable() {
    if (!Date::isWeekend($this->date) && !$this->isAdmin) {
        $table = '<row>
                <columns large="12">
                    <p>Fecha de Entrega: <strong>'.$this->date.'</strong></p>
                    <p>PMB: <strong>'.$this->pmb.'</strong>
                    <table><tr><th>Entrada</th><th>Remitente</th><th>Fecha Recepción</th><th>Autorizado</th></tr>';

        foreach ($this->packages as $package) {
            $table .= '<tr><td>'.$package['entrada'].'</td><td>'.$package['fromm'].'</td><td>'.$package['fecha_recepcion'].'</td><td>'.$package['nombre_autorizado'].'</td></tr>';
        }

        $table .= '    </table><hr/>
                    </columns>
                </row>';

        $this->table = $table;
    } else {
        $this->table = '';
    }
 }

 function getBody() {
   return $this->body;
 }
}

