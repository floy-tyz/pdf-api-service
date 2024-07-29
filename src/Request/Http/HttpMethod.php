<?php

namespace App\Request\Http;

enum HttpMethod: string
{
    case GET = 'GET';
    case POST = 'POST';
    case HEAD = 'HEAD';
    case OPTIONS = 'OPTIONS';
    case PATCH = 'PATCH';
    case PUT = 'PUT';
    case DELETE = 'DELETE';
}
