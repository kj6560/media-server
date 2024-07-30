@if ($errors->any())
<div class="card mb-4" style="margin: 10px; padding:10px;">
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif