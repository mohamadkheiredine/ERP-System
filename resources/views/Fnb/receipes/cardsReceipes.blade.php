@foreach($list_receipes as $index => $receipe_info)
    <div class="recipe-card card mb-3 border-0 shadow-sm"
     data-fi_id="{{ $receipe_info->fi_id }}"
     style="cursor:pointer;">

    <div class="card-body py-3">

        <div class="d-flex justify-content-between align-items-center">
            <h6 class="card-title fw-bold mb-0">{{ $receipe_info->fi_item_name }}</h6>
        </div>

    </div>
</div>

@endforeach
