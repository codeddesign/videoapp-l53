<?php

namespace App\Events;

use App\Models\Tag;
use App\Transformers\TagTransformer;
use Carbon\Carbon;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class CampaignEventReceived implements ShouldBroadcast
{
    use SerializesModels, AdminChannels;

    public $campaignId;
    public $source;
    public $tag;
    public $status;
    public $timestamp;

    private $userId;

    /**
     * Create a new event instance.
     *
     * @param $userId
     * @param $campaignId
     * @param $source
     * @param $status
     * @param Carbon $time
     * @param $tag
     */
    public function __construct($userId, $campaignId, $source, $status, $time, $tag = null)
    {
        $this->userId     = $userId;
        $this->campaignId = $campaignId;
        $this->source     = $source;
        $this->timestamp  = $time->timestamp * 1000;
        $this->status     = $status;

        if ($tag !== null) {
            $this->tag = (new TagTransformer)->transform(Tag::find($tag));
        }
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn()
    {
        return array_merge(
            [new PrivateChannel('user.'.$this->userId)],
            $this->getAdminChannels()
        );
    }
}
