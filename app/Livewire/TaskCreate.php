<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Task;

class TaskCreate extends Component
{
    public string $title = '';
    public ?string $description = null;
    public string $priority = 'Sedang';
    public ?string $due_date = null;

    protected $rules = [
        'title'       => 'required|string|max:255',
        'description' => 'nullable|string',
        'priority'    => 'required|in:Tinggi,Sedang,Rendah',
        'due_date'    => 'nullable|date',
    ];

    public function save()
    {
        $this->validate();

        $user = auth()->user();
        if (! $user) {
            return redirect()->route('login');
        }

        Task::create([
            'user_id'     => $user->id,
            'title'       => $this->title,
            'description' => $this->description,
            'priority'    => $this->priority,
            'due_date'    => $this->due_date,
            'completed'   => false,
        ]);

        $this->reset(['title', 'description', 'priority', 'due_date']);
        $this->priority = 'Sedang';

        // reload page supaya daftar tugas ke-update
        return redirect()->route('dashboard');
    }

    public function render()
    {
        // folder kecil: resources/views/livewire/task-create.blade.php
        return view('livewire.task-create');
    }
}
