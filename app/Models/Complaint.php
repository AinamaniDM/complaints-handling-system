<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'category', 'description', 'status'];

    const STATUS_PENDING     = 'Pending';
    const STATUS_IN_PROGRESS = 'In Progress';
    const STATUS_RESOLVED    = 'Resolved';

    public static function statuses(): array
    {
        return [self::STATUS_PENDING, self::STATUS_IN_PROGRESS, self::STATUS_RESOLVED];
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('category', 'ilike', "%{$keyword}%")
              ->orWhere('description', 'ilike', "%{$keyword}%")
              ->orWhereHas('user', function ($u) use ($keyword) {
                  $u->where('name', 'ilike', "%{$keyword}%")
                    ->orWhere('email', 'ilike', "%{$keyword}%");
              });
        });
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING     => 'badge-warning',
            self::STATUS_IN_PROGRESS => 'badge-info',
            self::STATUS_RESOLVED    => 'badge-success',
            default                  => 'badge-secondary',
        };
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
