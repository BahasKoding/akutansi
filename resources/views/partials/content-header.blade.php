<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h1 class="m-0">{{ $title ?? 'Dashboard' }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    @if(isset($breadcrumb))
                        @foreach($breadcrumb as $item)
                            @if($loop->last)
                                <li class="breadcrumb-item active" aria-current="page">{{ $item['title'] }}</li>
                            @else
                                <li class="breadcrumb-item"><a href="{{ $item['url'] }}">{{ $item['title'] }}</a></li>
                            @endif
                        @endforeach
                    @else
                        <li class="breadcrumb-item active" aria-current="page">{{ $title ?? 'Dashboard' }}</li>
                    @endif
                </ol>
            </div>
        </div>
    </div>
</div>
