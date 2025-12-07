<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Task;

class TaskItem extends Component
{
    public Task $task;

    public function toggle()
    {
        $this->task->update([
            'completed' => ! $this->task->completed,
        ]);
    }

    public function delete()
    {
        $this->task->delete();
    }

    public function render()
    {
        return view('livewire.task-item');
    }
}
