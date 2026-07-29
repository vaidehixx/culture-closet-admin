<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    protected $fillable = [
        'reporter_id', 'reported_id', 'product_id', 'reason', 'details', 'status', 'admin_notes',
    ];

    public function reporter(): BelongsTo { return $this->belongsTo(User::class, 'reporter_id'); }
    public function reported(): BelongsTo { return $this->belongsTo(User::class, 'reported_id'); }
    public function product(): BelongsTo  { return $this->belongsTo(Product::class); }
}
