<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surveys extends Model
{
    use HasFactory;

    public function survey_tags()
    {
        return $this->hasMany(SurveyTags::class, "id", "survey_id");
    }

}
