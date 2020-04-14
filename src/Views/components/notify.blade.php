@if(Session::has('danger'))
    <div class="callout callout-danger">
        {{ Session::get('danger') }}
        <button type="button"><i class="fas fa-times-circle"></i></button>
    </div>
@endif

@if(Session::has('info'))
    <div class="callout callout-info">
        {{ Session::get('info') }}
        <button type="button"><i class="fas fa-times-circle"></i></button>
    </div>
@endif

@if(Session::has('warning'))
    <div class="callout callout-warning">
        {{ Session::get('warning') }}
        <button type="button"><i class="fas fa-times-circle"></i></button>
    </div>
@endif

@if(Session::has('success'))
    <div class="callout callout-success">
        {{ Session::get('success') }}
        <button type="button"><i class="fas fa-times-circle"></i></button>
    </div>
@endif

@if(Session::has('primary'))
    <div class="callout callout-primary">
        {{ Session::get('primary') }}
        <button type="button"><i class="fas fa-times-circle"></i></button>
    </div>
@endif

@isset($errors)
    @if (@$errors->any())
        @foreach (@$errors->all() as $error)
            <div class="callout callout-danger">
                {{ $error }}
                <button type="button"><i class="fas fa-times-circle"></i></button>
            </div>
        @endforeach
    @endif
@endisset
