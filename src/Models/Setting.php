<?php

namespace IbrahimBedir\FilamentDynamicSettingsPage\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    public $fillable = ['key', 'display_name', 'type', 'group', 'value'];

    public $timestamps = false;
}
