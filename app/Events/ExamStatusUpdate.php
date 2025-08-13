<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ExamStatusUpdate implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $ruangUjianId;
    public $siswaId;
    public $action;
    public $data;

    /**
     * Create a new event instance.
     * 
     * @param int $ruangUjianId ID ruang ujian
     * @param int $siswaId ID siswa
     * @param string $action Aksi yang dilakukan (entered_room|save_answer|submit_exam|violation|tab_switch|screenshot_taken)
     * @param array $data Data tambahan terkait aksi
     */
    public function __construct($ruangUjianId, $siswaId, $action, array $data = [])
    {
        $this->ruangUjianId = $ruangUjianId;
        $this->siswaId = $siswaId;
        $this->action = $action;
        $this->data = $data;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('exam-room.' . $this->ruangUjianId),
        ];
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'ruangUjianId' => $this->ruangUjianId,
            'siswaId' => $this->siswaId,
            'action' => $this->action,
            'data' => $this->data,
            'timestamp' => now()->toIso8601String(),
        ];
    }
}
