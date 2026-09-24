<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BreakingTicker extends Model
{
    protected $fillable = ['ticker_text', 'link_url', 'is_active'];
}
