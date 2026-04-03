<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    // ── UPDATED: added category_id, attachment, attachment_type ──────────────
    protected $fillable = [
        'user_id', 'category_id', 'description',
        'status', 'attachment', 'attachment_type',
    ];

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

    // ── UPDATED: search now includes category name ────────────────────────────
    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('description', 'ilike', "%{$keyword}%")
              ->orWhereHas('category', fn($c) => $c->where('name', 'ilike', "%{$keyword}%"))
              ->orWhereHas('user', fn($u) =>
                  $u->where('name',  'ilike', "%{$keyword}%")
                    ->orWhere('email', 'ilike', "%{$keyword}%")
              );
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

    // ── NEW: attachment type helpers ──────────────────────────────────────────
    public function isImage(): bool { return $this->attachment_type === 'image'; }
    public function isPdf(): bool   { return $this->attachment_type === 'pdf'; }
    public function isAudio(): bool { return $this->attachment_type === 'audio'; }
    public function isVideo(): bool { return $this->attachment_type === 'video'; }

    // ── Relationships ─────────────────────────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ── NEW: category and comments relationships ──────────────────────────────
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->with('user')->oldest();
    }
}
