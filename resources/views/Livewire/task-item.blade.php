<div class="task-card" style="display:flex; justify-content:space-between; align-items:center;">
    <div>
        <div class="task-title {{ $task->completed ? 'completed' : '' }}">
            {{ $task->title }}
        </div>

        @if($task->description)
            <div class="task-desc">
                {{ $task->description }}
            </div>
        @endif

        <div style="display:flex; gap:8px; margin-top:6px; font-size:13px; opacity:.8;">
            <span>
                Jatuh tempo:
                {{ $task->due_date ? $task->due_date->format('d/m/Y') : '-' }}
            </span>
            <span>
                Prioritas:
                <strong>{{ $task->priority }}</strong>
            </span>
        </div>
    </div>

    <div style="display:flex; flex-direction:column; gap:6px;">
        <button wire:click="toggle" class="btn" style="padding:6px 12px; font-size:12px;">
            {{ $task->completed ? 'Tandai Belum' : 'Tandai Selesai' }}
        </button>
        <button wire:click="delete" class="btn" style="padding:6px 12px; font-size:12px; background:#fee2e2; color:#991b1b;">
            Hapus
        </button>
    </div>
</div>
