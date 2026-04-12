<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['complaint_id', 'user_id', 'body', 'attachment', 'attachment_type'];

    public function complaint() { return $this->belongsTo(Complaint::class); }
    public function user()      { return $this->belongsTo(User::class); }

    public function isFromAdmin(): bool { return $this->user->isAdmin(); }

    // Attachment type helpers
    public function isImage(): bool { return $this->attachment_type === 'image'; }
    public function isPdf(): bool   { return $this->attachment_type === 'pdf'; }
    public function isAudio(): bool { return $this->attachment_type === 'audio'; }
    public function isVideo(): bool { return $this->attachment_type === 'video'; }
    public function hasAttachment(): bool { return !is_null($this->attachment); }
}
