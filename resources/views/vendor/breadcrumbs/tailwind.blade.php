@unless ($breadcrumbs->isEmpty())
    <flux:breadcrumbs class="text-xs">
        @foreach ($breadcrumbs as $breadcrumb)
            @if($loop->last)
                <flux:breadcrumbs.item>{{$breadcrumb->title}}</flux:breadcrumbs.item>
            @else
                <flux:breadcrumbs.item href="{{$breadcrumb->url}}">{{$breadcrumb->title}}</flux:breadcrumbs.item>
            @endif
        @endforeach
    </flux:breadcrumbs>
@endunless
