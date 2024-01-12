@extends('layouts.brand-ambassador')

@section('title', 'Create Transactions')

@section('pre-title', 'Deployee')

@section('page-title', 'Create Transaction')

@section('content')
<form action="{{ route('brand-ambassador.data.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-12 mb-3">
            <label for="account" class="form-label">Account</label>
            <select name="account_id" id="account" class="form-select">
                <option value="" disabled selected >-- SELECT ACCOUNT -- </option>
                @foreach ($accounts as $account)
                    <option value="{{ $account->id }}">{{ $account->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12 mb-3 repeater">
            <div class="d-flex justify-content-between align-content-center ">
                <label for="location" class="form-label">Item</label>
                <button class="btn btn-primary" type="button" data-repeater-create>Add item</button>
            </div>
            <div data-repeater-list="items">
                <div class="row mb-2" data-repeater-item >
                    <div class="col-5">
                        <select name="item" id="" class="form-select col-6">
                            <option value="">-- SELECT ITEM --</option>
                            @foreach ($items as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-5">
                        <input type="text" class="form-control col-6" placeholder="Quantity" name="quantity">
                    </div>
                    <div class="col-2">
                        <a class="me-1 icon" href="#" data-repeater-delete><i class="ti ti-trash text-danger"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <input type="submit" value="Create Transaction" class="btn btn-primary w-100 btn-block">
    </div>
</form>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.repeater/1.2.1/jquery.repeater.min.js" integrity="sha512-foIijUdV0fR0Zew7vmw98E6mOWd9gkGWQBWaoA1EOFAx+pY+N8FmmtIYAVj64R98KeD2wzZh1aHK0JSpKmRH8w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    $(document).ready(function () {
        var $repeater = $('.repeater').repeater({
        })
    })

</script>
@endsection
