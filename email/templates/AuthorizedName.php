<?php
namespace MaspostAPI;
require_once(__DIR__.'/../../helpers/Helpers.php');

class AuthorizedName {
    public $body;
    private $pmb;
    private $table;
    private $content;

    function __construct($data) {
        date_default_timezone_set('America/Denver');
        $this->pmb = $data['pmb'];
        $this->packages = $data['packages'];
        $this->name = $data['name'];
        $this->title = '<h1 style="padding-top:20px;">Nuevo Nombre Autorizado</h1>';
        $this->previewText = 'Hemos recibido tu solicitud de autorización de entrega.';

        AuthorizedName::setContent();
        AuthorizedName::setTable();
        AuthorizedName::setBody();
    }

    function setBody() {
        $this->body = file_get_contents(__DIR__.'/foundations/simple.html');
        $this->body = str_replace('{CONTENT}', $this->content, $this->body);
        $this->body = str_replace('{TABLE}', $this->table, $this->body);
        $this->body = str_replace('{PREVIEW_TEXT}', $this->previewText, $this->body);
        $this->body = str_replace('{TITLE}', $this->title, $this->body);
    }

    function setContent() {
        $this->content = '<p style="margin-bottom: 40px;">Hemos autorizado a: <strong>'.$this->name.'</strong> para recoger los siguientes paquetes.</p>';
    }

    function setTable() {
        $table = '<row>
                <columns large="12">
                    <p>PMB: <strong>'.$this->pmb.'</strong>
                    <table style="font-size: 1rem; margin-top: 30px; border: none;"><tr style="background-color: #f6f6f6; font-weight: 500;"><th>Entrada</th><th>Remitente</th><th>Fecha Recepción</th><th>Autorizado</th></tr>';

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
