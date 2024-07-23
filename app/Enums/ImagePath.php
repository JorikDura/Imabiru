<?php

declare(strict_types=1);

namespace App\Enums;

enum ImagePath: string
{
    case UserPath = 'images/users/';
    case PostPath = 'images/posts/';
    case CommentPath = 'images/comments/';
}
