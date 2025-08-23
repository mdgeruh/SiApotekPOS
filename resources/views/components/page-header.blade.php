@php
    $pageTitle = \App\Helpers\AppSettingHelper::pageTitle();
    $pageIcon = \App\Helpers\AppSettingHelper::pageIcon();
@endphp

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="{{ $pageIcon }} mr-2"></i>
        {{ $pageTitle }}
    </h1>
    
    @if(isset($actions))
        <div class="d-flex">
            {{ $actions }}
        </div>
    @endif
</div>
