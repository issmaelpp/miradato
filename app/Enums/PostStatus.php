<?php

namespace App\Enums;

enum PostStatus: string
{
    case Borrador = 'borrador';
    case Publicado = 'publicado';
}
