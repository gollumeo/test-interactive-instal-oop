<?php

namespace app\Models;

use DateTime;

class Post extends Model
{
    public int $id;
    public static string $table = 'posts';
    public string $author;
    public string $title;
    public string $body;
    public string|DateTime $last_updated;
}