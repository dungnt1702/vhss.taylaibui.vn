<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RepairCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RepairCategoriesController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = RepairCategory::ordered()->paginate(20);
        return view('admin.repair_categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.repair_categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean'
        ]);

        $data = $request->all();
        $data['key'] = Str::slug($request->name, '_');
        $data['is_active'] = $request->has('is_active');

        RepairCategory::create($data);

        return redirect()->route('admin.repair-categories.index')
            ->with('success', 'Hạng mục đã được tạo thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(RepairCategory $repairCategory)
    {
        return view('admin.repair_categories.show', compact('repairCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RepairCategory $repairCategory)
    {
        return view('admin.repair_categories.edit', compact('repairCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RepairCategory $repairCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean'
        ]);

        $data = $request->all();
        $data['key'] = Str::slug($request->name, '_');
        $data['is_active'] = $request->has('is_active');

        $repairCategory->update($data);

        return redirect()->route('admin.repair-categories.index')
            ->with('success', 'Hạng mục đã được cập nhật thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RepairCategory $repairCategory)
    {
        // Check if category is being used
        $isUsed = \App\Models\VehicleTechnicalIssue::where('category', $repairCategory->key)->exists();
        
        if ($isUsed) {
            return redirect()->route('admin.repair-categories.index')
                ->with('error', 'Không thể xóa hạng mục này vì đang được sử dụng!');
        }

        $repairCategory->delete();

        return redirect()->route('admin.repair-categories.index')
            ->with('success', 'Hạng mục đã được xóa thành công!');
    }

    /**
     * Toggle active status
     */
    public function toggle(RepairCategory $repairCategory)
    {
        $repairCategory->update(['is_active' => !$repairCategory->is_active]);
        
        $status = $repairCategory->is_active ? 'kích hoạt' : 'vô hiệu hóa';
        return redirect()->route('admin.repair-categories.index')
            ->with('success', "Hạng mục đã được {$status} thành công!");
    }
}
