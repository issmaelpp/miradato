<?php

namespace App\Enums;

enum PostType: string
{
    case Noticia = 'noticia';
    case Opinion = 'opinion';
    case Comunidad = 'comunidad';
}
