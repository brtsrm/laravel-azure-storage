<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyTags extends Model
{
    use HasFactory;
    protected $table = "survey_tags";

    public function getTags()
    {
        return $this->hasMany(Tags::class, "id", "tags_id");
    }

}
