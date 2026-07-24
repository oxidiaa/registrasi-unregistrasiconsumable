<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Item;
use App\Models\FormItem;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    /**
     * Display a listing of the items and dashboard statistics.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $category = $request->input('category');

        // Statistics
        $stats = [
            'total' => Item::count(),
            'registered' => Item::where('status', 'registered')->count(),
            'unregistered' => Item::where('status', 'unregistered')->count(),
            'consumables' => Item::where('category', 'Consumable')->count(),
        ];

        // Base Query
        $query = Item::query();

        // Apply Search
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('item_code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Apply Status Filter
        if (in_array($status, ['registered', 'unregistered'])) {
            $query->where('status', $status);
        }

        // Apply Category Filter
        if ($category) {
            $query->where('category', $category);
        }

        // Fetch paginated results
        $items = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        // Get unique categories for the filter dropdown
        $categories = Item::select('category')->distinct()->pluck('category')->toArray();
        if (empty($categories)) {
            $categories = ['Consumable', 'Asset', 'Electronics', 'Office Stationery', 'Others'];
        }

        return view('dashboard', compact('items', 'stats', 'categories', 'search', 'status', 'category'));
    }

    /**
     * Store a newly created item in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        // Auto-generate code: BRG-YYYYMMDD-XXXX
        $today = now()->format('Ymd');
        
        // Find the latest item today to get the next sequential number
        $latestItem = Item::where('item_code', 'like', "BRG-{$today}-%")
            ->orderBy('item_code', 'desc')
            ->first();

        $nextNumber = 1;
        if ($latestItem) {
            // Extract sequence number
            $parts = explode('-', $latestItem->item_code);
            if (count($parts) === 3) {
                $nextNumber = intval($parts[2]) + 1;
            }
        }

        $itemCode = 'BRG-' . $today . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        Item::create([
            'item_code' => $itemCode,
            'name' => $validated['name'],
            'category' => $validated['category'],
            'description' => $validated['description'],
            'status' => 'registered',
            'registered_at' => now(),
        ]);

        return redirect()->route('dashboard')->with('success', 'Barang "' . $validated['name'] . '" berhasil didaftarkan dengan kode: ' . $itemCode);
    }

    /**
     * Unregister an item.
     */
    public function unregister(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        if ($item->status === 'unregistered') {
            return redirect()->route('dashboard')->with('error', 'Barang ini sudah berstatus unregistrasi.');
        }

        $validated = $request->validate([
            'unregistration_reason' => 'required|string|min:5|max:1000',
        ], [
            'unregistration_reason.required' => 'Alasan pembatalan registrasi wajib diisi.',
            'unregistration_reason.min' => 'Alasan pembatalan minimal 5 karakter.',
        ]);

        $item->update([
            'status' => 'unregistered',
            'unregistered_at' => now(),
            'unregistration_reason' => $validated['unregistration_reason'],
        ]);

        return redirect()->route('dashboard')->with('success', 'Registrasi barang "' . $item->name . '" berhasil dibatalkan (unregistered).');
    }

    /**
     * Display the Form Pendaftaran Barang Consumable page.
     */
    public function formRegistrasi()
    {
        $formItems = FormItem::orderBy('created_at', 'asc')->get();
        return view('form-registrasi', compact('formItems'));
    }

    /**
     * Store a new form item entry.
     */
    public function storeFormItem(Request $request)
    {
        $validated = $request->validate([
            'nama_barang'        => 'required|string|max:255',
            'kode_barang'        => 'nullable|string|max:100',
            'harga'              => 'nullable|numeric|min:0',
            'estimasi_usia_pakai'=> 'nullable|string|max:100',
            'kategori_penggunaan'=> 'nullable|string|max:100',
            'kategori_ukuran'    => 'nullable|string|max:100',
            'min'                => 'nullable|integer|min:0',
            'titik_order'        => 'nullable|integer|min:0',
            'max'                => 'nullable|integer|min:0',
            'is_b3'              => 'nullable|boolean',
            'is_non_b3'          => 'nullable|boolean',
        ]);

        $validated['is_b3']    = $request->has('is_b3');
        $validated['is_non_b3'] = $request->has('is_non_b3');

        FormItem::create($validated);

        return redirect()->route('form-registrasi')
            ->with('success', 'Data barang "' . $validated['nama_barang'] . '" berhasil ditambahkan.');
    }

    /**
     * Delete a form item entry.
     */
    public function deleteFormItem($id)
    {
        $item = FormItem::findOrFail($id);
        $name = $item->nama_barang;
        $item->delete();

        return redirect()->route('form-registrasi')
            ->with('success', 'Data "' . $name . '" berhasil dihapus.');
    }
}
