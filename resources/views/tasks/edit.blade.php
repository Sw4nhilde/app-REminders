@extends('layouts.app')

@section('content')
<div class="auth-wrapper">
    <div class="glass auth-card" style="padding:24px 22px 22px;">
        <div class="auth-header">
            <div class="auth-avatar">E</div>
            <div>
                <div class="badge-soft mb-1">
                    Edit Tugas
                </div>
                <h1 class="dashboard-title" style="font-size:20px;">
                    Ubah detail tugasmu
                </h1>
                <p class="dashboard-subtitle">
                    Sesuaikan judul, deskripsi, prioritas, atau tenggat tugas ini.
                </p>
            </div>
        </div>

        @if ($errors->any())
            <div style="background:#fee2e2; color:#991b1b; padding:10px 12px; border-radius:12px; font-size:13px; margin-bottom:12px;">
                {{ $errors->first() }}
            </div>
        @endif

        <form
            method="POST"
            action="{{ route('tasks.update', $task) }}"
            style="display:flex; flex-direction:column; gap:12px; margin-top:8px;"
        >
            @csrf
            @method('PUT')

            <div>
                <label for="title" style="font-size:13px; font-weight:600; display:block; margin-bottom:4px;">
                    Judul tugas
                </label>
                <input
                    id="title"
                    type="text"
                    name="title"
                    class="input"
                    value="{{ old('title', $task->title) }}"
                    required
                >
            </div>

            <div>
                <label for="description" style="font-size:13px; font-weight:600; display:block; margin-bottom:4px;">
                    Deskripsi (opsional)
                </label>
                <input
                    id="description"
                    type="text"
                    name="description"
                    class="input"
                    value="{{ old('description', $task->description) }}"
                >
            </div>

            <div>
                <label for="priority" style="font-size:13px; font-weight:600; display:block; margin-bottom:4px;">
                    Prioritas
                </label>
                <select
                    id="priority"
                    name="priority"
                    class="input"
                >
                    <option value="Tinggi" {{ old('priority', $task->priority) === 'Tinggi' ? 'selected' : '' }}>Tinggi</option>
                    <option value="Sedang" {{ old('priority', $task->priority) === 'Sedang' ? 'selected' : '' }}>Sedang</option>
                    <option value="Rendah" {{ old('priority', $task->priority) === 'Rendah' ? 'selected' : '' }}>Rendah</option>
                </select>
            </div>

            <div>
                <label for="due_date" style="font-size:13px; font-weight:600; display:block; margin-bottom:4px;">
                    Tanggal jatuh tempo
                </label>
                <input
                    id="due_date"
                    type="date"
                    name="due_date"
                    class="input"
                    value="{{ old('due_date', optional($task->due_date)->format('Y-m-d')) }}"
                >
            </div>

            <div style="display:flex; gap:8px; margin-top:8px;">
                <button type="submit" class="btn">
                    Simpan Perubahan
                </button>

                <a
                    href="{{ route('dashboard') }}"
                    class="btn"
                    style="background:#e5e7eb; color:#111827; text-decoration:none; padding:12px 24px;"
                >
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
