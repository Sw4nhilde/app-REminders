@if($show)
<div style="
    position:fixed; inset:0; background:rgba(0,0,0,0.4);
    display:flex; align-items:center; justify-content:center;
">
    <div style="background:white; padding:20px; border-radius:12px; width:350px;">

        <h3 style="font-weight:bold; margin-bottom:10px;">Edit Tugas</h3>

        <input wire:model="title" class="input" placeholder="Judul" style="margin-bottom:10px; width:100%;">
        <textarea wire:model="description" class="input" placeholder="Deskripsi" style="margin-bottom:10px; width:100%;"></textarea>

        <select wire:model="priority" class="input" style="margin-bottom:10px; width:100%;">
            <option value="Tinggi">Tinggi</option>
            <option value="Sedang">Sedang</option>
            <option value="Rendah">Rendah</option>
        </select>

        <input type="date" wire:model="due_date" class="input" style="margin-bottom:10px; width:100%;">

        <div style="display:flex; justify-content:space-between;">
            <button wire:click="save" class="btn">Simpan</button>
            <button wire:click="$set('show', false)" class="btn" style="background:#fee2e2; color:#991b1b;">
                Tutup
            </button>
        </div>

    </div>
</div>
@endif
