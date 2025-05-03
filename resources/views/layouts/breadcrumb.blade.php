<div class="row mb-2">
    <div class="col">
        <h2>{{ $breadcrumb->title ?? $breadcrumb ?? 'INI TITLE BREADCRUMB' }}</h2>
    </div>
    <div class="col">
        <ol class="breadcrumb float-sm-right border-0">
            <li class="breadcrumb-item active">SIMAGANG</li>
            @if(isset($breadcrumb->list) && is_array($breadcrumb->list))
                @foreach ($breadcrumb->list as $key => $value)
                    @if ($key == count($breadcrumb->list) - 1)
                        <li class="breadcrumb-item">{{ $value }}</li>
                    @else
                        <li class="breadcrumb-item active">{{ $value }}</li>
                    @endif
                @endforeach
            @endif
        </ol>
    </div>
</div>
