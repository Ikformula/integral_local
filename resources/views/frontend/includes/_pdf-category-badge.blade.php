@foreach($pdf_file->categories() as $p_category)
<span class="badge bg-{{ $p_category->parentCategory->name =='Fleet' ? 'navy' : 'maroon' }}">{{ $p_category->name }}</span>
@endforeach
