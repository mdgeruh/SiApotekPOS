@php
    $breadcrumbs = \App\Helpers\AppSettingHelper::breadcrumbs();
@endphp

@if(count($breadcrumbs) > 0)
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            @foreach($breadcrumbs as $breadcrumb)
                @if($breadcrumb['active'])
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ $breadcrumb['title'] }}
                    </li>
                @else
                    <li class="breadcrumb-item">
                        <a href="{{ $breadcrumb['url'] }}" class="text-decoration-none">
                            {{ $breadcrumb['title'] }}
                        </a>
                    </li>
                @endif
            @endforeach
        </ol>
    </nav>
@endif
