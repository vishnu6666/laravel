<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Spreadsheet extends Model
{
    public $timestamps = false;
    protected $table = 'spreadsheets';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected $fillable = [
      'spreadsheetId',
    ];
}
