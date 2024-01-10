<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use App\Models\WarehouseItem;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index(Request $request)
    {
        $hasBack = false;
        $total = Warehouse::count();

        $warehouses = Warehouse::with('items')->search($request->get('search', ''))->get();

        return view('admin.warehouses.index', compact('total', 'warehouses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'address' => 'required',
            'name' => 'required',
        ]);

        Warehouse::query()->create($data);

        toastr('Warehouse created successfully');

        return redirect()->route('admin.warehouses.index');
    }

    public function itemsStore(Request $request, Warehouse $warehouse)
    {
        $data = $request->validate([
            'name' => 'required',
            'quantity' => 'required|numeric|gt:0',
            'price' => 'required|numeric|gt:0',
            'description' => 'required'
        ]);

        $warehouse->items()->create($data);

        toastr('Sales for warehouse '. $warehouse->name .' created successfully');

        return redirect()->route('admin.warehouses.index');
    }

    public function deleteSales()
    {
        return redirect()->route('admin.warehouses.index');
    }

    public function itemsAdd(Request $request, WarehouseItem $warehouseItem)
    {
        $data = $request->validate(['quantity' => 'required|numeric|gt:0']);

        $warehouseItem->update(['quantity' => $warehouseItem->quantity + $data['quantity']]);

        toastr('Item'. $warehouseItem->name .' stocked successfully');

        return redirect()->route('admin.warehouses.index');
    }

    public function itemsUpdate(Request $request, WarehouseItem $warehouseItem)
    {
        $data = $request->validate([
            'name' => 'required',
            'price' => 'required|numeric|gt:0',
            'description' => 'required'
        ]);

        $warehouseItem->update($data);

        toastr('Item'. $warehouseItem->name .' updated successfully');

        return redirect()->route('admin.warehouses.index');
    }
}
