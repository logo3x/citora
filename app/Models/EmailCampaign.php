<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['created_by', 'subject', 'body_markdown', 'segment', 'status', 'scheduled_at', 'sent_at', 'recipients_count', 'opened_count'])]
class EmailCampaign extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'sent_at' => 'datetime',
            'recipients_count' => 'integer',
            'opened_count' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * @return HasMany<EmailCampaignRecipient, $this>
     */
    public function recipients(): HasMany
    {
        return $this->hasMany(EmailCampaignRecipient::class);
    }

    public function openRate(): float
    {
        if ($this->recipients_count === 0) {
            return 0.0;
        }

        return round(($this->opened_count / $this->recipients_count) * 100, 1);
    }
}
