<?php

namespace App\Enums;

enum ScrapedPostStatus: string
{
    case Pendiente = 'pendiente';
    case Procesado = 'procesado';
    case Publicado = 'publicado';
    case Error = 'error';
}
