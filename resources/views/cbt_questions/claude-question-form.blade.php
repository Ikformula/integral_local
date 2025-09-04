@extends('frontend.layouts.app')

@section('content')

<section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
    <div class="card text-bg-theme">

         <div class="card-header d-flex justify-content-between align-items-center p-3">
            <h4 class="m-0">Create New/Update CBT Question</h4>
            <div>
                <a href="{{ route('cbt_questions.cbt_question.index') }}" class="btn btn-primary" title="Show All CBT Question">
                    <span class="fa-solid fa-table-list" aria-hidden="true"></span>
                </a>
            </div>
        </div>


        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <ul class="list-unstyled mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

                <form action="{{ $formAction }}" method="POST" id="questionForm">
                @csrf
                @if(isset($cbtQuestion))
                    @method('PUT')
                @endif

                <!-- Question fields -->
                    <div class="mb-3 row">
                        <label for="question" class="col-form-label text-lg-end col-lg-2 col-xl-3">Question</label>
                        <div class="col-lg-10 col-xl-9">
                            <textarea class="form-control{{ $errors->has('question') ? ' is-invalid' : '' }}" name="question" id="question" required="true" placeholder="Enter question here...">{{ old('question', isset($cbtQuestion) ? optional($cbtQuestion)->question : '') }}</textarea>
                            {!! $errors->first('question', '<div class="invalid-feedback">:message</div>') !!}
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="cbt_subject_id" class="col-form-label text-lg-end col-lg-2 col-xl-3">CBT Subject</label>
                        <div class="col-lg-10 col-xl-9">
                            <select class="form-control form-select{{ $errors->has('cbt_subject_id') ? ' is-invalid' : '' }}" id="cbt_subject_id" name="cbt_subject_id" required="true">
                                <option value="" style="display: none;" {{ old('cbt_subject_id', (isset($cbtQuestion) ? optional($cbtQuestion)->cbt_subject_id : '') ?: '') == '' ? 'selected' : '' }} disabled selected>Select cbt subject</option>
                                @foreach ($CbtSubjects as $CbtSubject => $key)
                                    <option value="{{ $key }}" {{ old('cbt_subject_id', (isset($cbtQuestion) ? optional($cbtQuestion)->cbt_subject_id : '')) == $key ? 'selected' : '' }}>
                                        {{ $CbtSubject }}
                                    </option>
                                @endforeach
                            </select>
                            {!! $errors->first('cbt_subject_id', '<div class="invalid-feedback">:message</div>') !!}
                        </div>
                    </div>

                    <!-- Options section -->
                    <div id="optionsContainer">
                        <h4>Options</h4>
                        @if(isset($cbtQuestion) && $cbtQuestion->cbtOptions->count() > 0)
                            @foreach($cbtQuestion->cbtOptions as $index => $option)
                                <div class="option-group mb-3">
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" name="options[{{ $index }}][body]" required placeholder="Enter option text" value="{{ $option->body }}">
                                        </div>
                                        <div class="col-lg-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="options[{{ $index }}][is_correct]" value="1" {{ $option->is_correct ? 'checked' : '' }}>
                                                <label class="form-check-label">Correct</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-2">
                                            <button type="button" class="btn btn-danger btn-sm remove-option">Remove</button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <button type="button" id="addOption" class="btn btn-secondary mb-3">Add Option</button>

                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Save Question and Options</button>
                    </div>
                </form>


        </div>
    </div>

                </div>
            </div>
        </div>
    </section>
@endsection


@push('after-scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const optionsContainer = document.getElementById('optionsContainer');
            const addOptionBtn = document.getElementById('addOption');
            let optionCount = {{ isset($cbtQuestion) ? $cbtQuestion->cbtOptions->count() : 0 }};

            addOptionBtn.addEventListener('click', function() {
                addNewOption();
            });

            optionsContainer.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-option')) {
                    e.target.closest('.option-group').remove();
                }
            });

            function addNewOption() {
                const optionHtml = `
            <div class="option-group mb-3">
                <div class="row">
                    <div class="col-lg-8">
                        <input type="text" class="form-control" name="options[${optionCount}][body]" required placeholder="Enter option text">
                    </div>
                    <div class="col-lg-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="options[${optionCount}][is_correct]" value="1">
                            <label class="form-check-label">Correct</label>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <button type="button" class="btn btn-danger btn-sm remove-option">Remove</button>
                    </div>
                </div>
            </div>
        `;
                optionsContainer.insertAdjacentHTML('beforeend', optionHtml);
                optionCount++;
            }
        });
    </script>
@endpush
