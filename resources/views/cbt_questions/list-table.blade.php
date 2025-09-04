<div class="table-responsive">

    <table class="table table-striped ">
        <thead>
        <tr>
            <th>Question</th>
            <th>Subject</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($cbtQuestions as $cbtQuestion)
            <tr>
                <td>{{ $cbtQuestion->question }}</td>
                <td>{{ $cbtQuestion->CbtSubject->name }}</td>
                <td class="text-end">

                    <form method="POST" action="{!! route('cbt_questions.cbt_question.destroy', $cbtQuestion->id) !!}" accept-charset="UTF-8">
                        <input name="_method" value="DELETE" type="hidden">
                        {{ csrf_field() }}

                        <div class="btn-group btn-group-sm" role="group">
                            <a href="{{ route('cbt_questions.cbt_question.show', $cbtQuestion->id ) }}" class="btn btn-info" title="Show CBT Question">
                                <span class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></span>
                            </a>
                            <a href="{{ route('cbt_questions.cbt_question.edit', $cbtQuestion->id ) }}" class="btn btn-primary" title="Edit CBT Question">
                                <span class="fa-regular fa-pen-to-square" aria-hidden="true"></span>
                            </a>

                            <button type="submit" class="btn btn-danger" title="Delete CBT Question" onclick="return confirm(&quot;Click Ok to delete CBT Question.&quot;)">
                                <span class="fa-regular fa-trash-can" aria-hidden="true"></span>
                            </button>
                        </div>

                    </form>

                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>
