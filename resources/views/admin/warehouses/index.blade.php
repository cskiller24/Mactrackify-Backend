@extends('layouts.admin')

@section('title', 'Warehouses')

@section('page-title', 'Warehouses')

@section('content-header')
<div class="row">
    <div class="col">
        <x-search />
    </div>

    <div class="col-6 d-flex justify-content-end align-items-center">
        <div class="fs-3 mx-3">
            Total Warehouses: {{ $total }}
        </div>
        <a href="" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#warehouse-create-modal">
            <i class="ti ti-plus icon"></i>
            Warehouse
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="accordion" id="accordionExample">
    @forelse ($warehouses as $warehouse)
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingOne">
            <div class="d-flex justify-content-between">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne{{ $warehouse->id }}" aria-expanded="true" aria-controls="collapseOne{{ $warehouse->id }}">
                    {{ $warehouse->name. ' - ' .$warehouse->address }}
                </button>
            </div>
        </h2>
        <div id="collapseOne{{ $warehouse->id }}" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
            <div class="accordion-body">
                <a href="" class="btn btn-outline-primary mb-3" data-bs-toggle="modal" data-bs-target="#sales-create-{{$warehouse->id}}">
                    <i class="ti ti-plus icon"></i>
                    Create Product
                </a>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Description</th>
                            <th scope="col">Price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($warehouse->items as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->description }}</td>
                                <td>{{ $item->price }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>
                                    <a href="#" class="" data-bs-toggle="modal" data-bs-target="#items-add-{{$item->id}}" title="Add stock">
                                        <i class="ti ti-circle-plus icon"></i>
                                    </a>
                                    <a href="#" class="" data-bs-toggle="modal" data-bs-target="#items-update-{{$item->id}}" title="Edit Stock">
                                        <i class="ti ti-pencil icon"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <h1 class="mt-4 text-center">No entries found.</h1>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @empty
        <h1 class="mt-3 text-center card p-5" >No entries found</h1>
    @endforelse
@endsection

@section('modals')
<div class="modal fade" id="warehouse-create-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" action="{{ route('admin.warehouses.store') }}" method="POST">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Create Warehouse</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Enter the name">
                    </div>
                    <div class="col-12 mb-3">
                        <label for="location" class="form-label">Address</label>
                        <input type="text" name="address" id="address" class="form-control" placeholder="Enter the address">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Create</button>
            </div>
        </form>
    </div>
</div>

@foreach ($warehouses as $warehouse)
<div class="modal fade" id="sales-create-{{$warehouse->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" action="{{ route('admin.warehouses.items.store', $warehouse->id) }}" method="POST">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Create Sales for {{ $warehouse->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Enter the name">
                    </div>
                    <div class="col-12 mb-3">
                        <label for="description" class="form-label">Description</label>
                        <input type="text" name="description" id="description" class="form-control" placeholder="Enter the description">
                    </div>
                    <div class="col-12 mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" name="price" id="price" class="form-control" placeholder="Enter the quantity">
                    </div>
                    <div class="col-12 mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="text" name="quantity" id="quantity" class="form-control" placeholder="Enter the quantity">
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Create</button>
            </div>
        </form>
    </div>
</div>

    @foreach ($warehouse->items as $item)
    <div class="modal fade" id="items-update-{{$item->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" action="{{ route('admin.warehouses.items.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Sales for {{ $item->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Enter the name" value="{{ $item->name }}">
                        </div>
                        <div class="col-12 mb-3">
                            <label for="description" class="form-label">Description</label>
                            <input type="text" name="description" id="description" class="form-control" placeholder="Enter the description" value="{{ $item->description }}">
                        </div>
                        <div class="col-12 mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" name="price" id="price" class="form-control" placeholder="Enter the price" value="{{ $item->price }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
    @endforeach

    @foreach ($warehouse->items as $item)
    <div class="modal fade" id="items-add-{{$item->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" action="{{ route('admin.warehouses.items.add', $item->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add quantity to {{ $item->name }} item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="location" class="form-label">Quantity</label>
                            <input type="text" name="quantity" id="quantity" class="form-control" placeholder="Enter the quantity you like to add ">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add quantity</button>
                </div>
            </form>
        </div>
    </div>
    @endforeach
@endforeach
@endsection
