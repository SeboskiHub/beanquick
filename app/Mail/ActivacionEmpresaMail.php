<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\SolicitudEmpresa;

class ActivacionEmpresaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $solicitud;
    public $link;

    /**
     * Create a new message instance.
     */
    public function __construct(SolicitudEmpresa $solicitud, string $link)
    {
        $this->solicitud = $solicitud;
        $this->link = $link;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this
            ->subject('Activación de cuenta — BeanQuick')
            ->markdown('emails.activacion_empresa');
    }
}
