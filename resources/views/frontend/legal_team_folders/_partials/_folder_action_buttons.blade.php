{{--<a href="{{ route('frontend.legal_team_folders.show', $legal_team_foldersItem->id) }}" class="btn btn-sm btn-primary">View</a>--}}
<button class="btn btn-sm btn-info" data-toggle="modal" data-target="#modalEdit-legal_team_folders-{{ $legal_team_foldersItem->id }}">Edit</button>
<form action="{{ route('frontend.legal_team_folders.destroy', $legal_team_foldersItem->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
    @csrf @method('DELETE')
    <button class="btn btn-sm btn-danger">Delete</button>
</form>

<div class="modal fade" id="modalEdit-legal_team_folders-{{ $legal_team_foldersItem->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg"><div class="modal-content">
            <form action="{{ route('frontend.legal_team_folders.update', $legal_team_foldersItem->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Folder</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group"><label>Name</label>
                        <input type="text" class="form-control" name="name" value="{{ $legal_team_foldersItem->name }}"></div>
                    <div class="form-group"><label>Move Folder</label>
                        <select class="form-control" name="parent_id">
                            <option value="">-- Select --</option>
                            @foreach(\App\Models\LegalTeamFolder::where('id', '!=', $legal_team_foldersItem->id)->get() as $opt)
                                <option value="{{ $opt->id }}" {{ $opt->id==$legal_team_foldersItem->parent_id?'selected':'' }}>{{ $opt->name }}</option>
                            @endforeach
                        </select></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div></div>
</div>
