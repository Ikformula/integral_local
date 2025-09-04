<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\ContentCategory;
use App\Models\PdfCategory;
use App\Models\PdfFile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \App\Http\Controllers\Traits\OutgoingMessagesTrait;

class FCNIController extends Controller
{
    use OutgoingMessagesTrait;

    public function index(Request $request)
    {
        $user = auth()->user();
        $staff_member = $user->staff_member;

        if(!$staff_member){
            $staff_ara_id = null;
        }else{
            $staff_ara_id = $staff_member->staff_ara_id;
        }

        if($user->can('manage pilot elibrary')){
            if($request->filled('fleet') && in_array($request->fleet, ['Q400', '737'])){
                $fleet_categories = ContentCategory::where('name', $request->fleet)->get();
                if(!$fleet_categories){
                    return back(404);
                }

            } else {
                $fleet_categories = ContentCategory::all();
            }
            return view('frontend.pilots_library.management.index')->with(['fleet_categories' => $fleet_categories]);
        }else if($user->can('view Q400 PDFs')){
//            $pdf_files = PdfFile::where('fleet', 'Q400')->latest()->get();
            $fleet_categories = ContentCategory::where('name', 'Q400')->get();
            return view('frontend.pilots_library.index')->with(['fleet_categories' => $fleet_categories, 'staff_ara_id' => $staff_ara_id]);
        }else if($user->can('view 737 PDFs')){
            $fleet_categories = ContentCategory::where('name', '737')->get();
            return view('frontend.pilots_library.index')->with(['fleet_categories' => $fleet_categories, 'staff_ara_id' => $staff_ara_id]);
        }else{
            return redirect()->route('frontend.user.dashboard')->withErrors('No documents available for your viewing');
        }
    }


    public function create()
    {
        $content_categories = ContentCategory::where('parent_category_id', null)->get();
        return view('frontend.pilots_library.management.create')->with(['content_categories' => $content_categories]);
    }

    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'file' => 'required|file|mimes:pdf',
            'category' => 'required'
        ]);

        // Get the file
        $file = $request->file('file');

        // Generate a unique filename
        $filename = $request->name.'-'.$request->year.'-'.time().'-'.uniqid() . '.' . $file->getClientOriginalExtension();

        // Move the file to the public folder
        $file->move('fq_pdfs', $filename);

        $pdf_file = new PdfFile();
        $user = auth()->user();
        $staff_member = $user->staff_member;

        if(!$staff_member){
            $pdf_file->staff_ara_id = null;
        }else{
            $pdf_file->staff_ara_id = $staff_member->staff_ara_id;
        }

        $pdf_file->name = $request->name;
        $pdf_file->filename = $filename;
        $pdf_file->year = $request->year;
//        $pdf_file->fleet = $request->fleet;
//        $pdf_file->category = $request->category;
        $pdf_file->save();

        // fleet categories
        $fleet_parent_category = ContentCategory::where('name', 'Fleet')->first();
        $fleet_children_categories = $fleet_parent_category->childrenCategory->pluck('id');
        $fleet_children_categories = $fleet_children_categories->toArray();

        foreach($request->category as $category){
            $inserts[] = [
              'pdf_file_id' => $pdf_file->id,
              'content_category_id' => $category,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if(in_array($category, $fleet_children_categories)){
                $category = ContentCategory::find($category);
                $category_name = $category->name;

                $users = User::whereHas('permissions', function ($query) use ($category_name) {
                    $query->where('name', 'view '.$category_name.' PDFs');
                })->get();

                if ($users->count()) {
                    foreach ($users as $user) {
                        if($user->email == 'ikechukwu.asuquo@arikair.com') {
                            unset($data);
                            $data['subject'] = config('app.name') . " - New Document for Reading";
                            $data['greeting'] = "Dear " . $user->first_name;
                            $data['line'][] = "A new PDF has been uploaded for your reading:";
                            $data['line'][] = "- Name: " . $pdf_file->name;
                            $data['line'][] = "- Year: " . $pdf_file->year;
                            $data['line'][] = "- Category: " . $category_name;
                            $data['action_url'] = route('frontend.pilotLibrary.show', $pdf_file);
                            $data['action_text'] = "Read now";
                            $data['to'] = $user->email;
                            $data['to_name'] = $user->name;

                            $this->storeMessage($data, $user->id);
                        }
                    }
                }
            }
        }

        PdfCategory::insert($inserts);



        return redirect()->route('frontend.pilotLibrary.index')->withFlashSuccess('PDF Uploaded Successfully and email queued to concerned Pilots');
    }

    public function show(PdfFile $pdf_file)
    {
        $user = auth()->user();
        $staff_member = $user->staff_member;

        if(!$staff_member){
            $staff_ara_id = null;
            $read_count = null;
        }else{
            $staff_ara_id = $staff_member->staff_ara_id;
            $read_count = staff_read_pdf($staff_ara_id, $pdf_file->id);
        }

        return view('frontend.pilots_library.show')->with([
            'pdf_file' => $pdf_file,
            'staff_ara_id' => $staff_ara_id,
            'read_count' => $read_count
            ]);
    }

    public function markAsRead(Request $request)
    {
        $validated = $request->validate([
           'pdf_file_id' => ['required', 'integer', 'exists:pdf_files,id']
        ]);

        $user = auth()->user();
        $staff_member = $user->staff_member;

//        dd($staff_member);

        if(!$staff_member){
            $staff_ara_id = null;
        }else {
            $staff_ara_id = $staff_member->staff_ara_id;

            $read = DB::table('pdf_reads')
                ->insert([
                    'staff_ara_id' => $staff_ara_id,
                    'pdf_id' => $request->pdf_file_id,
                    'read_at' => Carbon::now(),
                    'opened_at' => $request->opened_at
                ]);
        }
        return redirect()->route('frontend.pilotLibrary.index');
    }

    public function destroy(PdfFile $pdf_file)
    {
        $pdf_file->delete();
        return redirect()->route('frontend.pilotLibrary.index')->withFlashSuccess('PDF File deleted');
    }
}
