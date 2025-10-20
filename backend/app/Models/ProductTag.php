<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductTag extends Model
{
    /**
     * Get the product that owns the tag.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    //
}
