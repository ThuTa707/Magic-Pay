@if ($errors->has('fail'))

<div class="container">
    <div class="alert alert-danger text-center" role="alert">
        <strong> {{ $errors->first('fail') }}</strong>
        </button>
    </div>
</div>

@endif