<?php namespace Jcc\Scanlogin\Models;

use Model;

/**
 * Scan Model
 */
class Scan extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'jcc_scanlogin_scans';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Validation rules for attributes
     */
    public $rules = [];

    /**
     * @var array Attributes to be cast to native types
     */
    protected $casts = [];

    /**
     * @var array Attributes to be cast to JSON
     */
    protected $jsonable = [];

    /**
     * @var array Attributes to be appended to the API representation of the model (ex. toArray())
     */
    protected $appends = [];

    /**
     * @var array Attributes to be removed from the API representation of the model (ex. toArray())
     */
    protected $hidden = [];

    /**
     * @var array Attributes to be cast to Argon (Carbon) instances
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'start_use_at',
        'expired_at'
    ];

    const LOGIN_TYPE_GONGZHONGHAO = 'gongzhonghao';
    const LOGIN_TYPE_WEIXIN = 'weixin';
    const LOGIN_TYPE_MINI = 'mini';

    public static $loginTypeMaps = [
        self::LOGIN_TYPE_GONGZHONGHAO => '公众号登录',
        self::LOGIN_TYPE_WEIXIN       => '微信登录',
        self::LOGIN_TYPE_MINI         => '小程序登录',
    ];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $hasOneThrough = [];
    public $hasManyThrough = [];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [
        'img' => ['System\Models\File']
    ];
    public $attachMany = [];
}
