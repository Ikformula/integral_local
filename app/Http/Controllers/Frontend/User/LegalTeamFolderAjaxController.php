<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\LegalTeamDocument;
use App\Models\LegalTeamExternalLawyer;
use App\Models\LegalTeamFolder;
use App\Models\LegalTeamFolderAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LegalTeamFolderAjaxController extends Controller
{
    public function index()
    {
        $legal_team_folders = LegalTeamFolder::with([
            'parent_idRelation',
        ])->get();

        return view('frontend.legal_team_folders.index', compact('legal_team_folders'));
    }

    public function store(Request $request)
    {
        LegalTeamFolder::create($request->all());
        return back()->withFlashSuccess('Folder created successfully.');
    }

    public function show($id)
    {
        $folder = LegalTeamFolder::findOrFail($id);
        $legal_team_folder_accesses = LegalTeamFolderAccess::where('folder_id', $folder->id)->get();
        $external_lawyers_users = LegalTeamExternalLawyer::all();

        return view('frontend.legal_team_folders.show', compact('folder', 'legal_team_folder_accesses', 'external_lawyers_users'));
    }

    public function update(Request $request, $id)
    {
        $legal_team_folders = LegalTeamFolder::findOrFail($id);
        $legal_team_folders->update($request->all());
        return back()->withFlashSuccess('Folder updated successfully.');
    }

    public function destroy($id)
    {
        LegalTeamFolder::destroy($id);
        return back()->withFlashSuccess('Folder deleted successfully.');
    }

    public function fileUpload(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:100'],
            'file_uploaded' => ['required', 'mimes:doc,docx,pdf,txt,jpg,jpeg,png', 'max:250000']
        ]);

        $file_name = Str::slug($request->title.' '.now()->toDayDateTimeString(), '_').'.'.$request->file('file_uploaded')->extension();
        $size = $request->file('file_uploaded')->getSize();
        $size_in_kilobytes = round($size / 1024, 2);
        $size_in_megabytes = round($size / 1048576, 2);

        $path = $request->file('file_uploaded')->storeAs('legal_external_docs', $file_name);
        $legal_doc = new LegalTeamDocument();
        $legal_doc->title = $request->title;
        $legal_doc->description = $request->description;
        $legal_doc->remarks = $request->remarks;
        $legal_doc->user_id = auth()->id();
        $legal_doc->file_name = $path;
        $legal_doc->folder_id = $request->folder_id;
        $legal_doc->size_in_kilobytes = $size_in_kilobytes;
        $legal_doc->size_in_megabytes = $size_in_megabytes;
        $legal_doc->save();

        return redirect()->back()->withFlashSuccess('File Uploaded Successfully');
    }

    public function fileManagerLink(Request $request)
    {
        // Example: get user and allowed folders from your app logic
        $user = auth()->user();
        $allowedFolders = [
            'folder1',
//            'public/lfm/path2/to/folder/accessibleFolder2',
        ];


        $user->otp = generateNumericOTP(6);
        $user->save();
        // Must match the secret in tinyfilemanager.php
        $secret = 'YOUR_SHARED_SECRET_HERE';

        $params = [
            'user'     => $user->id,
            'email'    => $user->email,
            'folders'  => base64_encode(json_encode($allowedFolders)),
            'readonly' => 0, // or 1 if you want readonly
            'expires'  => time() + 3600, // 1 hour from now
            'otp'      => $user->otp
        ];
        // Build signature
        $query = $params;
        ksort($query);
        $base = http_build_query($query);
        $signature = hash_hmac('sha256', $base, $secret);

        // Add signature to params
        $params['signature'] = $signature;

        // Build the final URL
        $url = url('/lfm/tinyfilemanager.php') . '?' . http_build_query($params);

        // You can return this as a link or redirect
        return redirect($url);
        // Or: return view('your-view', ['fileManagerUrl' => $url]);
    }
}
