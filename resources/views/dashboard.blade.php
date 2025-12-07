@extends('layouts.app')

@section('content')
<div>
    <div class="main-shell">

        @php
            /** @var \App\Models\User|null $user */
            $user  = auth()->user();
            $streak = 0;

            $tasks = $user
                ? $user->tasks()->latest()->get()
                : collect();

            $totalTasks = $tasks->count();
            $doneTasks  = $tasks->where('completed', true)->count();
            $productiv  = $totalTasks ? round($doneTasks / $totalTasks * 100) : 0;

            $initials = $user?->name
                ? collect(explode(' ', $user->name))
                    ->map(fn ($p) => mb_substr(trim($p), 0, 1))
                    ->join('')
                : 'TA';
        @endphp

        {{-- HEADER --}}
        <div class="dashboard-header">
            <div class="glass" style="padding:24px 22px 22px; position:relative; overflow:hidden;">
                <div class="dashboard-top">
                    <div style="display:flex; gap:14px; align-items:center;">
                        <div class="auth-avatar" style="width:52px;height:52px;font-size:20px;">
                            {{ $initials }}
                        </div>
                        <div>
                            <div class="badge-soft mb-1">
                                Mode Mahasiswa • Tracking Tugas Harian
                            </div>
                            <div class="dashboard-title">
                                Halo, {{ $user?->name ?? 'Pengguna' }}!
                            </div>
                            <div class="dashboard-subtitle">
                                Kelola tugas kuliah, project, dan aktivitasmu dalam satu tempat.
                            </div>
                        </div>
                    </div>

                    <div style="display:flex; gap:8px; align-items:center;">
                        <button
                            type="button"
                            class="btn"
                            style="padding:8px 16px; border-radius:999px; font-size:13px;"
                            onclick="Alpine.store('darkMode').toggle()"
                        >
                            Ubah tema
                        </button>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button
                                type="submit"
                                class="btn"
                                style="padding:8px 16px; border-radius:999px; font-size:13px; background:#fee2e2; color:#991b1b;"
                            >
                                Logout
                            </button>
                        </form>
                    </div>
                </div>

                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-label">Streak</div>
                        <div class="stat-value">{{ $streak }}</div>
                        <div class="stat-extra">hari berturut-turut aktif</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Tugas selesai</div>
                        <div class="stat-value">{{ $doneTasks }}</div>
                        <div class="stat-extra">
                            dari {{ $totalTasks }} tugas
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Produktivitas</div>
                        <div class="stat-value">{{ $productiv }}%</div>
                        <div class="stat-extra">
                            target 60% per hari 💪
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- FORM TAMBAH TUGAS --}}
        <div class="glass" style="padding:22px 22px 18px; margin-bottom:24px;">
            <div class="tasks-header">
                <div>
                    <div class="tasks-title">Tambah Tugas Baru</div>
                    <div class="tasks-subtitle">
                        Catat tugas kuliah, project, atau aktivitas lain yang ingin kamu selesaikan.
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('tasks.store') }}" style="display:flex; gap:8px; flex-wrap:wrap; align-items:flex-start;">
                @csrf

                <input
                    type="text"
                    name="title"
                    class="input"
                    placeholder="Judul tugas..."
                    style="flex:2;"
                    required
                >

                <input
                    type="text"
                    name="description"
                    class="input"
                    placeholder="Deskripsi (opsional)"
                    style="flex:3;"
                >

                <select name="priority" class="input" style="max-width:140px;">
                    <option value="Tinggi">Tinggi</option>
                    <option value="Sedang" selected>Sedang</option>
                    <option value="Rendah">Rendah</option>
                </select>

                <input
                    type="date"
                    name="due_date"
                    class="input"
                    style="max-width:160px;"
                >

                <button type="submit" class="btn">
                    + Tambah Tugas
                </button>
            </form>

            @if ($errors->any())
                <div style="color:#b91c1c; font-size:13px; margin-top:6px;">
                    {{ $errors->first() }}
                </div>
            @endif
        </div>

        {{-- DAFTAR TUGAS --}}
        <div class="glass" style="padding:22px 22px 18px;">
            <div class="tasks-header" style="margin-top:0;">
                <div>
                    <div class="tasks-title">Daftar Tugas</div>
                    <div class="tasks-subtitle">
                        {{ $totalTasks ? "$totalTasks tugas tersimpan" : 'Belum ada tugas tersimpan' }}
                    </div>
                </div>
            </div>

            @if($tasks->isEmpty())
                <div class="empty-state">
                    Belum ada tugas.<br>
                    Yuk mulai hari ini biar jadwal kuliahmu lebih tertata. ✨
                </div>
            @else
                <div style="display:flex; flex-direction:column; gap:14px; margin-top:10px;">
                    @foreach ($tasks as $task)
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

                            {{-- Kanan: tombol aksi, dibuat sama lebar --}}
                            <div style="display:flex; flex-direction:column; gap:6px; width:130px;">
                                {{-- Edit --}}
                                <a
                                    href="{{ route('tasks.edit', $task) }}"
                                    class="btn"
                                    style="padding:6px 12px; font-size:12px; display:block; width:100%; text-align:center;"
                                >
                                    Edit
                                </a>

                                {{-- Toggle selesai --}}
                                <form action="{{ route('tasks.toggle', $task) }}" method="POST">
                                    @csrf
                                    <button
                                        type="submit"
                                        class="btn"
                                        style="padding:6px 12px; font-size:12px; display:block; width:100%; text-align:center;"
                                    >
                                        {{ $task->completed ? 'Tandai Belum' : 'Tandai Selesai' }}
                                    </button>
                                </form>

                                {{-- Hapus --}}
                                <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Yakin hapus tugas ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        class="btn"
                                        style="padding:6px 12px; font-size:12px; display:block; width:100%; text-align:center; background:#fee2e2; color:#991b1b;"
                                    >
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
