@extends('backend.layouts.app')

@push('after-styles')
    <script src="https://cdn.tiny.cloud/1/3tlnxd9meewmqbvzz1s9lrst4tuif34yf7hfohhuthzlqsxp/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
@endpush

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h2>Edit {{ $model }}</h2>
                    </div>
                    <div class="card-body">
                        {!! html()->modelForm($record, 'PATCH', route('admin.database_admin.update', [$model, $record->id]))->open() !!}
                        @foreach($columns as $column)
                            @php
                                $columnType = $columnTypesMapping[$column_info[$column]];
                                $value = $record->$column;
                                $maxlength = $columnMaxLengths[$column];
                                $comment = $columnComments[$column];
                                $isRequired = in_array($column, $requiredColumns);
                            @endphp
                            <div class="form-group">
                                {!! html()->label(ucfirst($column)) !!}
                                @if($columnType === 'number')
                                    {!! html()->input('number', $column)->class('form-control')->value($value)->maxlength($maxlength) !!}
                                @elseif($columnType === 'textarea')
                                    {!! html()->textarea($column)->class('form-control rich-text')->value($value)->maxlength($maxlength) !!}
                                @elseif($columnType === 'datetime-local')
                                    {!! html()->datetime($column)->class('form-control')->value($value) !!}
                                @elseif($columnType === 'date')
                                    {!! html()->date($column)->class('form-control')->value($value) !!}
                                @elseif($columnType === 'time')
                                    {!! html()->time($column)->class('form-control')->value($value) !!}
                                @elseif($columnType === 'select' && isset($enumValues[$column]))
                                    {!! html()->select($column, $enumValues[$column], $value)->class('form-control') !!}
                                @else
                                    {!! html()->input('text', $column)->class('form-control')->value($value)->maxlength($maxlength) !!}
                                @endif
                                @if ($comment)
                                    <small class="form-text text-muted">{{ $comment }}</small>
                                @endif
                                @if ($isRequired)
                                    <span class="text-danger">*</span>
                                @endif
                            </div>
                        @endforeach

                        {!! html()->submit('Update')->class('btn btn-primary') !!}
                        {!! html()->closeModelForm() !!}
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('after-scripts')
    <script>
        tinymce.init({
            selector: 'textarea.rich-text',
            plugins: 'ai tinycomments mentions anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed permanentpen footnotes advtemplate advtable advcode editimage tableofcontents mergetags powerpaste tinymcespellchecker autocorrect a11ychecker typography inlinecss',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | align lineheight | tinycomments | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
            tinycomments_mode: 'embedded',
            tinycomments_author: '{{ app_name() }}',
            mergetags_list: [
                { value: 'First.Name', title: 'First Name' },
                { value: 'Email', title: 'Email' },
            ],
            ai_request: (request, respondWith) => respondWith.string(() => Promise.reject("See docs to implement AI Assistant")),
        });
    </script>
@endpush
