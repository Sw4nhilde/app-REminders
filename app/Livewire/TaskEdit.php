<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Task;

class TaskEdit extends Component
{
    public $show = false;
    public ?Task $task = null;

    public $title;
    public $description;
    public $priority;
    public $due_date;

    protected $listeners = ['editTask' => 'open'];

    public function open($data)
    {
        $this->task = Task::find($data['id']);

        $this->title       = $this->task->title;
        $this->description = $this->task->description;
        $this->priority    = $this->task->priority;
        $this->due_date    = $this->task->due_date?->format('Y-m-d');

        $this->show = true;
    }

    public function save()
    {
        $this->task->update([
            'title'       => $this->title,
            'description' => $this->description,
            'priority'    => $this->priority,
            'due_date'    => $this->due_date,
        ]);

        $this->dispatch('taskUpdated');

        $this->show = false;
    }

    public function render()
    {
        return view('livewire.task-edit');
    }
}
