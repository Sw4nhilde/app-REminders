<div>
    <form
        wire:submit.prevent="save"
        class="task-form"
        style="display:flex; gap:8px; flex-wrap:wrap; align-items:flex-start;"
    >
        <input
            type="text"
            wire:model="title"
            class="input"
            placeholder="Judul tugas..."
            style="flex:2;"
        >

        <input
            type="text"
            wire:model="description"
            class="input"
            placeholder="Deskripsi (opsional)"
            style="flex:3;"
        >

        <select wire:model="priority" class="input" style="max-width:140px;">
            <option value="Tinggi">Tinggi</option>
            <option value="Sedang">Sedang</option>
            <option value="Rendah">Rendah</option>
        </select>

        <input
            type="date"
            wire:model="due_date"
            class="input"
            style="max-width:160px;"
        >

        <button type="submit" class="btn">
            + Tambah Tugas
        </button>
    </form>

    @error('title')
        <div style="color:#b91c1c; font-size:13px; margin-top:6px;">
            {{ $message }}
        </div>
    @enderror
</div>
