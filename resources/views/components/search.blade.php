<form id="search-model">
    <span class="input-icon">
        <input id="indexSearch" type="text" class="form-control" placeholder="Search" name="search" value="{{ request()->search ?? '' }}">
        <span class="input-icon-addon me-3">
            <i class="ti ti-search icon"></i>
        </span>
    </span>
</form>
