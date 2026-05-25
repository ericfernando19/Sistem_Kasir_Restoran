<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function index(Request $request)
    {
        $query = Table::query();

        if ($request->search) {
            $query->where('table_number', 'like', "%{$request->search}%");
        }

        if ($request->status && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $tables = $query->latest()->paginate(10);

        return view('tables.index', compact('tables'));
    }

    public function create()
    {
        return view('tables.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'table_number' => 'required|string|max:10|unique:tables',
            'capacity' => 'required|integer|min:1|max:50',
        ]);

        Table::create($validated);

        return redirect()->route('tables.index')
            ->with('success', 'Meja berhasil ditambahkan.');
    }

    public function edit(Table $table)
    {
        return view('tables.form', compact('table'));
    }

    public function update(Request $request, Table $table)
    {
        $validated = $request->validate([
            'table_number' => 'required|string|max:10|unique:tables,table_number,'.$table->id,
            'capacity' => 'required|integer|min:1|max:50',
        ]);

        $table->update($validated);

        return redirect()->route('tables.index')
            ->with('success', 'Meja berhasil diperbarui.');
    }

    public function destroy(Table $table)
    {
        if ($table->transactions()->whereIn('status', ['pending', 'processing'])->count() > 0) {
            return back()->with('error', 'Meja tidak dapat dihapus karena memiliki pesanan aktif.');
        }

        $table->delete();

        return redirect()->route('tables.index')
            ->with('success', 'Meja berhasil dihapus.');
    }

    public function toggleStatus(Table $table)
    {
        $newStatus = $table->status === 'available' ? 'reserved' : 'available';
        $table->update(['status' => $newStatus]);

        $statusLabels = ['available' => 'Tersedia', 'occupied' => 'Terisi', 'reserved' => 'Dipesan'];

        return redirect()->route('tables.index')
            ->with('success', "Status meja {$table->table_number} diubah menjadi {$statusLabels[$newStatus]}.");
    }
}
