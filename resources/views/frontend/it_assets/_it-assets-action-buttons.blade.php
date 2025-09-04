<a href="{{ route('frontend.it_assets.show', $it_asset) }}" class="btn btn-xs btn-primary">View</a>
<a href="{{ route('frontend.it_assets.edit', $it_asset) }}" class="btn btn-xs btn-secondary">Edit</a>
<form method="POST" class="form-inline" action="{{ route('frontend.it_assets.destroy', $it_asset) }}" onsubmit="return confirm('Are you sure you want to delete this Asset? This process can not be reversed')">
    @csrf
    <button type="submit" class="btn btn-xs btn-danger">Delete</button>
</form>
