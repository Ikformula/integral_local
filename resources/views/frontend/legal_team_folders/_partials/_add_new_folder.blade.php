<div class="modal fade" id="modalCreate-legal_team_folders" tabindex="-1">
    <div class="modal-dialog modal-lg"><div class="modal-content">
            <form action="{{ route('frontend.legal_team_folders.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New Folder</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group"><label>Name</label>
                        <input type="text" class="form-control" name="name"></div>
                    @if(isset($parent_folder_id))
                        <input type="hidden" name="parent_id" value="{{ $parent_folder_id }}">
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </form>
        </div></div>
</div>
