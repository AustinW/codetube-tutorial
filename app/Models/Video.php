<?php

namespace App\Models;

use App\Models\Channel;
use App\Models\VideoView;
use App\Traits\VoteableTrait;
use App\Traits\OrderableTrait;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Laravel\Scout\Searchable;

class Video extends Model
{
    use SoftDeletes, Searchable, VoteableTrait, OrderableTrait;
    
    protected $fillable = [
        'title',
        'description',
        'uid',
        'video_filename',
        'video_id',
        'processed',
        'visibility',
        'allow_votes',
        'allow_comments',
        'processed_percentage',
    ];

    public function toSearchableArray() {
        $properties = $this->toArray();

        $properties['visible'] = $this->isProcessed() && $this->isPublic();
        $properties['hullo'] = true;
        $properties['private'] = $this->isPrivate();

        return $properties;
    }

    public function channel() {
        return $this->belongsTo(Channel::class);
    }

    public function views() {
        return $this->hasMany(VideoView::class);
    }

    public function votes() {
        return $this->morphMany(Vote::class, 'voteable');
    }

    public function comments() {
        return $this->morphMany(Comment::class, 'commentable')->whereNull('reply_id');
    }

    public function viewCount() {
        return $this->views()->count();
    }
    
    public function getRouteKeyName() {
        return 'uid';
    }
    
    public function isProcessed() {
        return (bool) $this->processed;
    }
    
    public function processedPercentage() {
        return $this->processed_percentage;
    }
    
    public function votesAllowed() {
        return (bool) $this->allow_votes;
    }
    
    public function commentsAllowed() {
        return (bool) $this->allow_comments;
    }
    
    public function getThumbnail() {
        if (!$this->isProcessed()) {
            return config('codetube.buckets.videos') . '/default_thumbnail.png';
        } else {
            return config('codetube.buckets.videos') . '/' . $this->video_id . '_1.jpg';
        }
    }

    public function isPrivate() {
        return $this->visibility === 'private';
    }

    public function isPublic() {
        return $this->visibility === 'public';
    }

    public function ownedByUser(User $user) {
        return $this->channel->user->id === $user->id;
    }

    public function canBeAccessed(User $user = null) {
        if (!$user && $this->isPrivate()) {
            return false;
        }

        if ($user && $this->isPrivate() && ($user->id !== $this->channel->user_id)) {
            return false;
        }

        return true;
    }

    public function getStreamUrl() {
        return config('codetube.buckets.videos') . '/' . $this->video_id . '.mp4';
    }

    public function voteFromUser(User $user) {
        return $this->votes()->where('user_id', $user->id);
    }

    public function scopeProcessed($query) {
        return $query->where('processed', true);
    }

    public function scopePublic($query) {
        return $query->where('visibility', 'public');
    }

    public function scopeVisible($query) {
        return $query->processed()->public();
    }
}
