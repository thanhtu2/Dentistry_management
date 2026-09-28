<?php

namespace App\Models;

use App\Traits\ResponseTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;

class Level extends Model
{
    use HasFactory, ResponseTraits;

    protected $table = 'level';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name'
    ];

    private $model;
    private $url;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->url = Config::get('app.image.url');
    }

    public function getLevels() {
        return Level::all();
    }
}
