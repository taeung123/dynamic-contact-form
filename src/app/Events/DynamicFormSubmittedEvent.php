<?php

namespace VCComponent\Laravel\ConfigContact\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DynamicFormSubmittedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $contact_form_data;
    public $contact_form_value;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($contact_form_data, $contact_form_value)
    {
        $this->contact_form_data = $contact_form_data;
        $this->contact_form_value = $contact_form_value;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
