<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Item;
use App\Models\FormItem;
use App\Models\User;
use App\Models\FormApproval;
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
     * Re-sequence form numbers so that only forms with items are kept and strictly numbered per department (01, 02, 03...).
     * Empty forms (0 items) are eliminated from sequence.
     */
    private function resequenceFormNumbers()
    {
        $allFormItems = FormItem::orderBy('created_at', 'asc')->orderBy('id', 'asc')->get();
        if ($allFormItems->isEmpty()) {
            return [];
        }

        // Group items by their existing form_number preserving insertion order
        $grouped = [];
        foreach ($allFormItems as $item) {
            $fNo = $item->form_number ?: '01';
            if (!isset($grouped[$fNo])) {
                $grouped[$fNo] = [];
            }
            $grouped[$fNo][] = $item;
        }

        $map = [];
        $deptSeqs = [];
        $userDeptTag = strtoupper(auth()->user()->department ?? auth()->user()->name ?? 'PRODUCTION');
        $monthYear = date('m-Y');

        foreach ($grouped as $oldFormNo => $items) {
            if (count($items) === 0) {
                continue;
            }

            // Extract department tag and month-year if present
            $parts = explode('/', $oldFormNo);
            $dept = (count($parts) >= 2 && !empty($parts[1])) ? strtoupper(trim($parts[1])) : $userDeptTag;
            $my = (count($parts) >= 3 && !empty($parts[2])) ? trim($parts[2]) : $monthYear;

            $key = $dept . '_' . $my;
            if (!isset($deptSeqs[$key])) {
                $deptSeqs[$key] = 1;
            } else {
                $deptSeqs[$key]++;
            }

            $newSeqStr = str_pad($deptSeqs[$key], 2, '0', STR_PAD_LEFT);
            $newFormNo = "{$newSeqStr}/{$dept}/{$my}";

            $map[$oldFormNo] = $newFormNo;

            foreach ($items as $item) {
                if ($item->form_number !== $newFormNo) {
                    $item->update(['form_number' => $newFormNo]);
                }
            }

            if ($oldFormNo !== $newFormNo) {
                FormApproval::where('form_number', $oldFormNo)->update(['form_number' => $newFormNo]);
            }
        }

        return $map;
    }

    /**
     * Display the Form Pendaftaran Barang Consumable page.
     */
    public function formRegistrasi(Request $request)
    {
        $activeTab = $request->query('tab');
        $userRole = strtoupper(auth()->user()->role ?? '');
        if ($activeTab === 'account-master' && !in_array($userRole, ['MASTER', 'ADMIN'])) {
            return redirect()->route('form-registrasi')->with('error', 'Akses ditolak. Fitur Account Master hanya dapat diakses oleh Role Master.');
        }

        $resequenceMap = $this->resequenceFormNumbers();
        $formItems = FormItem::with('user')->orderBy('created_at', 'asc')->orderBy('id', 'asc')->get();
        $users = User::orderBy('id', 'asc')->get();

        // Auto-create FormApproval record for each unique form_number in form_items if not exists
        $distinctForms = $formItems->pluck('form_number')->filter()->unique();
        foreach ($distinctForms as $fNo) {
            $firstItem = $formItems->firstWhere('form_number', $fNo);
            $creator = $firstItem?->user;
            FormApproval::firstOrCreate(
                ['form_number' => $fNo],
                [
                    'user_id'          => $creator?->id ?? auth()->id(),
                    'requestor_name'   => $firstItem?->created_by_name ?? $creator?->name ?? (auth()->user()->name ?? 'User'),
                    'requestor_dept'   => $firstItem?->created_by_dept ?? $creator?->department ?? (auth()->user()->department ?? 'Production'),
                    'form_date'        => $firstItem?->created_at ? $firstItem->created_at->format('d-m-Y') : date('d-m-Y'),
                    'status'           => 'BUTUH APPROVAL STAFF',
                    'user_signed_at'   => $firstItem?->created_at ?? now(),
                    'user_signer_name' => $firstItem?->created_by_name ?? (auth()->user()->name ?? 'User'),
                    'user_comment'     => 'Formulir pendaftaran diajukan.',
                ]
            );
        }

        $formApprovals = FormApproval::with('user')->get();

        $activeFormNoParam = $request->query('form');
        if ($activeFormNoParam && isset($resequenceMap[$activeFormNoParam])) {
            $activeFormNoParam = $resequenceMap[$activeFormNoParam];
        }

        return view('form-registrasi', compact('formItems', 'users', 'formApprovals', 'activeFormNoParam'));
    }

    /**
     * Store a new form item entry.
     */
    public function storeFormItem(Request $request)
    {
        if (!$request->has('is_b3') && !$request->has('is_non_b3')) {
            return back()->withErrors(['kategori' => 'Pilih salah satu kategori (B3 atau NON B3).'])->withInput();
        }

        if ($request->has('is_b3') && $request->has('is_non_b3')) {
            return back()->withErrors(['kategori' => 'Hanya boleh memilih salah satu kategori (B3 atau NON B3).'])->withInput();
        }

        $validated = $request->validate([
            'form_number'        => 'nullable|string|max:100',
            'kode_barang'        => 'required|string|max:100',
            'nama_barang'        => 'required|string|max:255',
            'harga'              => 'required|numeric|min:0',
            'estimasi_usia_pakai'=> 'required|string|max:100',
            'kategori_penggunaan'=> 'required|string|max:100',
            'kategori_ukuran'    => 'required|string|max:100',
            'min'                => 'required|integer|min:0',
            'titik_order'        => 'required|integer|min:0',
            'max'                => 'required|integer|min:0',
            'lead_time'          => 'required|string|max:100',
            'is_b3'              => 'nullable|boolean',
            'is_non_b3'          => 'nullable|boolean',
        ], [
            'kode_barang.required'         => 'Kode barang wajib diisi.',
            'nama_barang.required'         => 'Nama barang wajib diisi.',
            'harga.required'               => 'Harga wajib diisi.',
            'estimasi_usia_pakai.required' => 'Estimasi usia pakai wajib diisi.',
            'kategori_penggunaan.required' => 'Kategori penggunaan wajib diisi.',
            'kategori_ukuran.required'     => 'Kategori ukuran wajib diisi.',
            'min.required'                 => 'Min wajib diisi.',
            'titik_order.required'         => 'Titik order wajib diisi.',
            'max.required'                 => 'Max wajib diisi.',
            'lead_time.required'           => 'Lead time wajib diisi.',
        ]);

        $validated['is_b3']           = $request->has('is_b3');
        $validated['is_non_b3']        = $request->has('is_non_b3');
        $validated['user_id']         = auth()->id();
        $validated['created_by_name'] = auth()->user()->name ?? 'User';
        $validated['created_by_dept'] = auth()->user()->department ?? 'Production';

        $targetForm = $request->input('form_number');
        FormItem::create($validated);

        $map = $this->resequenceFormNumbers();
        if ($targetForm && isset($map[$targetForm])) {
            $targetForm = $map[$targetForm];
        }

        // Ensure FormApproval exists for the targetForm
        if ($targetForm) {
            FormApproval::firstOrCreate(
                ['form_number' => $targetForm],
                [
                    'user_id'          => auth()->id(),
                    'requestor_name'   => auth()->user()->name ?? 'User',
                    'requestor_dept'   => auth()->user()->department ?? 'Production',
                    'form_date'        => date('d-m-Y'),
                    'status'           => 'BUTUH APPROVAL STAFF',
                    'user_signed_at'   => now(),
                    'user_signer_name' => auth()->user()->name ?? 'User',
                    'user_comment'     => 'Formulir pendaftaran diajukan.',
                ]
            );
        }

        $redirectParams = $targetForm ? ['form' => $targetForm] : [];

        return redirect()->route('form-registrasi', $redirectParams)
            ->with('success', 'Data barang "' . $validated['nama_barang'] . '" berhasil ditambahkan.')
            ->with('show_add_more_prompt', true);
    }

    /**
     * Handle approval submission for a form with real database persistence.
     */
    public function approveForm(Request $request)
    {
        $request->validate([
            'form_number' => 'required|string',
            'role'        => 'required|string|in:staff,accounting,warehouse,user',
            'name'        => 'required|string|max:255',
            'comment'     => 'nullable|string|max:1000',
        ]);

        $formNo = $request->input('form_number');
        $role = strtolower($request->input('role'));
        $name = trim($request->input('name'));
        $comment = trim($request->input('comment')) ?: 'Disetujui.';

        $currentUser = auth()->user();
        $currentUserRole = strtoupper(trim($currentUser->role ?? ''));
        $isMaster = in_array($currentUserRole, ['MASTER', 'ADMIN']);

        $approval = FormApproval::firstOrCreate(
            ['form_number' => $formNo],
            [
                'user_id'          => $currentUser->id,
                'requestor_name'   => $currentUser->name,
                'requestor_dept'   => $currentUser->department ?? 'Production',
                'form_date'        => date('d-m-Y'),
                'status'           => 'BUTUH APPROVAL STAFF',
                'user_signed_at'   => now(),
                'user_signer_name' => $currentUser->name,
                'user_comment'     => 'Formulir diajukan.',
            ]
        );

        $msg = 'Status persetujuan berhasil diperbarui.';

        if ($role === 'staff') {
            if (!$isMaster && !str_contains($currentUserRole, 'STAFF')) {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Akses Ditolak: Hanya Role Staff atau Master yang dapat menyetujui tahap ini.'], 403);
                }
                return back()->with('error', 'Akses Ditolak: Hanya Role Staff atau Master yang dapat menyetujui tahap ini.');
            }

            // Department authorization for Staff: Staff can only approve forms from their own department
            if (!$isMaster) {
                $userDept = strtoupper(trim($currentUser->department ?? ''));
                $formDept = strtoupper(trim($approval->requestor_dept ?? ''));

                if (!$formDept && str_contains($formNo, '/')) {
                    $parts = explode('/', $formNo);
                    $formDept = isset($parts[1]) ? strtoupper(trim($parts[1])) : '';
                }

                if ($userDept && $formDept && $userDept !== $formDept) {
                    $errMsg = "Akses Ditolak: Anda login sebagai Staff Departemen {$userDept}. Anda hanya berwenang menyetujui formulir dari departemen Anda sendiri (Form {$formNo} berasal dari Departemen {$formDept}).";
                    if ($request->wantsJson() || $request->ajax()) {
                        return response()->json(['success' => false, 'message' => $errMsg], 403);
                    }
                    return back()->with('error', $errMsg);
                }
            }

            $approval->staff_signed_at = now();
            $approval->staff_signer_name = $name;
            $approval->staff_comment = $comment;
            $approval->status = 'APPROVAL ACCOUNTING';
            $approval->save();

            $msg = "Form $formNo berhasil disetujui oleh Staff ($name). Tahap selanjutnya: Approval Accounting.";
        } elseif ($role === 'accounting') {
            if (!$isMaster && !str_contains($currentUserRole, 'ACC')) {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Akses Ditolak: Hanya Role Accounting atau Master yang dapat menyetujui tahap ini.'], 403);
                }
                return back()->with('error', 'Akses Ditolak: Hanya Role Accounting atau Master yang dapat menyetujui tahap ini.');
            }

            if (!$approval->staff_signed_at && !$isMaster) {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Akses Ditolak: Tahap Staff harus disetujui terlebih dahulu.'], 422);
                }
                return back()->with('error', 'Akses Ditolak: Tahap Staff harus disetujui terlebih dahulu.');
            }

            $approval->accounting_signed_at = now();
            $approval->accounting_signer_name = $name;
            $approval->accounting_comment = $comment;
            $approval->status = 'REGISTRASI WAREHOUSE';
            $approval->save();

            $msg = "Form $formNo berhasil disetujui oleh Accounting ($name). Tahap selanjutnya: Registrasi Warehouse.";
        } elseif ($role === 'warehouse') {
            if (!$isMaster && !str_contains($currentUserRole, 'WAREHOUSE')) {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Akses Ditolak: Hanya Role Warehouse Consumable atau Master yang dapat menyelesaikan registrasi ini.'], 403);
                }
                return back()->with('error', 'Akses Ditolak: Hanya Role Warehouse Consumable atau Master yang dapat menyelesaikan registrasi ini.');
            }

            if (!$approval->accounting_signed_at && !$isMaster) {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Akses Ditolak: Tahap Accounting harus disetujui terlebih dahulu.'], 422);
                }
                return back()->with('error', 'Akses Ditolak: Tahap Accounting harus disetujui terlebih dahulu.');
            }

            $approval->warehouse_signed_at = now();
            $approval->warehouse_signer_name = $name;
            $approval->warehouse_comment = $comment;
            $approval->status = 'TELAH DIDAFTARKAN';
            $approval->save();

            $msg = "Form $formNo telah berhasil diregistrasi oleh Warehouse Consumable ($name). Proses Selesai.";
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success'  => true,
                'message'  => $msg,
                'approval' => $approval,
            ]);
        }

        return redirect()->route('form-registrasi', ['tab' => 'proses-approval', 'form' => $formNo])
            ->with('success', $msg);
    }

    /**
     * Delete an entire form / checksheet permanently.
     */
    public function deleteFormChecksheet(Request $request)
    {
        $formNo = $request->input('form_number');
        if (!$formNo) {
            return redirect()->route('form-registrasi', ['tab' => 'data-view'])->with('error', 'Form number tidak valid.');
        }

        FormItem::where('form_number', $formNo)->forceDelete();
        FormApproval::where('form_number', $formNo)->delete();

        $this->resequenceFormNumbers();

        return redirect()->route('form-registrasi', ['tab' => 'data-view'])
            ->with('success', 'Formulir "' . $formNo . '" berhasil dihapus secara permanen.');
    }

    /**
     * Delete a form item entry.
     */
    public function deleteFormItem($id)
    {
        $item = FormItem::findOrFail($id);
        $name = $item->nama_barang;
        $targetForm = $item->form_number;
        $item->forceDelete();

        $map = $this->resequenceFormNumbers();
        if ($targetForm && isset($map[$targetForm])) {
            $targetForm = $map[$targetForm];
        } else {
            // Form became empty after item deletion. Redirect to first available form with items if any.
            $firstItem = FormItem::orderBy('created_at', 'asc')->first();
            $targetForm = $firstItem ? $firstItem->form_number : null;
        }

        $redirectParams = $targetForm ? ['form' => $targetForm] : [];

        return redirect()->route('form-registrasi', $redirectParams)
            ->with('success', 'Data "' . $name . '" berhasil dihapus.');
    }

    /**
     * Store a new user account in database.
     */
    public function storeUser(Request $request)
    {
        $userRole = strtoupper(auth()->user()->role ?? '');
        if (!in_array($userRole, ['MASTER', 'ADMIN'])) {
            return redirect()->route('form-registrasi')->with('error', 'Akses ditolak. Hanya role Master yang dapat membuat akun pengguna.');
        }

        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'username'   => 'nullable|string|max:100|unique:users,email',
            'department' => 'required|string|max:100',
            'role'       => 'required|string|max:100',
            'password'   => 'required|string|min:4',
        ], [
            'name.required'       => 'User Name wajib diisi.',
            'department.required' => 'Department wajib diisi.',
            'role.required'       => 'Role wajib diisi.',
            'password.required'   => 'Password wajib diisi.',
            'password.min'        => 'Password minimal 4 karakter.',
            'username.unique'     => 'Username / Login ID sudah digunakan oleh pengguna lain.',
        ]);

        $username = trim($request->input('username'));
        if (!$username) {
            $username = strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', trim($validated['name'])));
            $baseUsername = $username;
            $counter = 1;
            while (User::where('email', $username)->exists()) {
                $username = $baseUsername . '_' . $counter;
                $counter++;
            }
        }

        User::create([
            'name'       => $validated['name'],
            'email'      => $username,
            'department' => $validated['department'],
            'role'       => $validated['role'],
            'status'     => 'Aktif',
            'password'   => bcrypt($validated['password']),
        ]);

        return redirect()->route('form-registrasi', ['tab' => 'account-master'])
            ->with('success', 'Akun pengguna "' . $validated['name'] . '" berhasil dibuat.');
    }

    /**
     * Update an existing user account in database.
     */
    public function updateUser(Request $request, $id)
    {
        $currentUserRole = strtoupper(auth()->user()->role ?? '');
        if (!in_array($currentUserRole, ['MASTER', 'ADMIN'])) {
            return redirect()->route('form-registrasi')->with('error', 'Akses ditolak. Hanya role Master yang dapat mengubah akun pengguna.');
        }

        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'username'   => 'nullable|string|max:100|unique:users,email,' . $user->id,
            'department' => 'required|string|max:100',
            'role'       => 'required|string|max:100',
            'password'   => 'nullable|string|min:4',
        ], [
            'name.required'       => 'User Name wajib diisi.',
            'department.required' => 'Department wajib diisi.',
            'role.required'       => 'Role wajib diisi.',
            'username.unique'     => 'Username / Login ID sudah digunakan oleh pengguna lain.',
            'password.min'        => 'Password minimal 4 karakter.',
        ]);

        $user->name = $validated['name'];
        if (!empty($validated['username'])) {
            $user->email = trim($validated['username']);
        }
        $user->department = $validated['department'];
        $user->role = $validated['role'];

        if (!empty($validated['password'])) {
            $user->password = bcrypt($validated['password']);
        }

        $user->save();

        return redirect()->route('form-registrasi', ['tab' => 'account-master'])
            ->with('success', 'Akun pengguna "' . $user->name . '" berhasil diperbarui.');
    }

    /**
     * Delete a user account from database.
     */
    public function deleteUser($id)
    {
        $currentUserRole = strtoupper(auth()->user()->role ?? '');
        if (!in_array($currentUserRole, ['MASTER', 'ADMIN'])) {
            return redirect()->route('form-registrasi')->with('error', 'Akses ditolak. Hanya role Master yang dapat menghapus akun pengguna.');
        }

        $user = User::findOrFail($id);

        if (auth()->id() === $user->id) {
            return redirect()->route('form-registrasi', ['tab' => 'account-master'])
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('form-registrasi', ['tab' => 'account-master'])
            ->with('success', 'Akun "' . $name . '" berhasil dihapus.');
    }
}
