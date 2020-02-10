<?php
namespace MaspostAPI;
require_once(__DIR__.'/../../helpers/Helpers.php');


class ConfirmExpressPickup {
    public $body;
    private $date;
    private $pmb;
    private $table;
    private $isAdmin;
    private $title;
    private $previewText;

    public $translations;
    private $content;

    function __construct($data, $isAdmin = false) {
        date_default_timezone_set('America/Denver');
        $this->date = $data['fecha'];
        $this->isAdmin = $isAdmin;
        $this->pmb = $data['paquetes'][0]['pmb'];
        $this->packages = $data['paquetes'];
        $this->expressId = $data['express_id'];
        $this->title = '<h1 style="padding-top:20px;">Confirmación de Entrega Expres en Fin de Semana</h1>';
        $this->previewText = 'Confirmado: Entrega Express en Fin de Semana.';

        ConfirmExpressPickup::setContent();
        ConfirmExpressPickup::setTable();
        ConfirmExpressPickup::setBody();
    }

    function setBody() {
        $this->body = file_get_contents(__DIR__.'/foundations/simple.html');
        $this->body = str_replace('{CONTENT}', $this->content, $this->body);
        $this->body = str_replace('{TABLE}', $this->table, $this->body);
        $this->body = str_replace('{PREVIEW_TEXT}', $this->previewText, $this->body);
        $this->body = str_replace('{TITLE}', $this->title, $this->body);
    }

    function setContent() {
        $this->content = '<p style="margin-bottom: 40px;">Hemos recibido tu solicitud de entrega express y si tenemos disponibilidad de horario. A continuación confirmamos los detalles de tu entrega en fin de semana. Te esperamos.</p>';
    }

    function setTable() {
        $table = '<row>
                <columns large="12">
                    <p>Fecha de Entrega: <strong>'.$this->date.'</strong></p>
                    <p>PMB: <strong>'.$this->pmb.'</strong>
                    <table align="center" width="100%" style="font-size: 1rem; margin-top: 30px; border: none; width:100%;"><tr style="background-color: #f6f6f6; font-weight: 500;"><th>Entrada</th><th>Remitente</th><th>Fecha Recepción</th><th>Autorizado</th></tr>';

        foreach ($this->packages as $package) {
            $table .= '<tr><td>'.$package['entrada'].'</td><td>'.$package['fromm'].'</td><td>'.$package['fecha_recepcion'].'</td><td>'.$package['nombre_autorizado'].'</td></tr>';
        }

        $table .= '    </table><hr/>
                    </columns>
                </row>';

        $this->table = $table;
    }

    function getBody() {
        return $this->body;
    }
}
