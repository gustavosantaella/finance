<?php
namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class BaseMongoModel extends Model {

    protected $connection = 'mongodb';
}
