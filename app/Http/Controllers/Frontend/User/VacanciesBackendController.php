<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
//use App\Mail\VacancyMail;
use App\Models\JobApplication;
use App\Models\Vacancy;
use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Traits\OutgoingMessagesTrait;
use Illuminate\Support\Str;

class VacanciesBackendController extends Controller
{
    use OutgoingMessagesTrait;

    public function index()
    {
        return view('frontend.hr_service_now.job_vacancies_backend.index')->with([
            'vacancies' => Vacancy::latest()->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
           'date_advertised' => ['required'],
           'date_of_closing' => ['required', 'after:date_advertised'],
        ]);
        $arr = [];
        if($request->hasFile('job_description_doc')) {
            $timestamp = now()->format('Y-m-d_H-i-s');
            $fileName = Str::slug($request->position, '_').'_'. $timestamp . '.' . $request->job_description_doc->extension();
            $request->job_description_doc->move(public_path('/job_vacancies/job_description_docs'), $fileName);
            $arr['job_description_doc_path'] = $fileName;
        }
        $vacancy = Vacancy::create(array_merge($request->all(), $arr));

        return redirect()->route('frontend.vacancies.backend.index')->withFlashSuccess('Vacancy stored');
    }

    public function edit(Vacancy $vacancy)
    {
        return view('frontend.hr_service_now.job_vacancies_backend.edit', compact('vacancy'));
    }

    public function update(Request $request, Vacancy $vacancy)
    {
        $validated = $request->validate([
            'date_advertised' => ['required'],
            'date_of_closing' => ['required', 'after:date_advertised'],
        ]);

//        $vacancy = Vacancy::find($request->vacancy_id);
//        if(!$vacancy)
//            return redirect()->route('frontend.vacancies.backend.index')->withErrors('Vacancy not found');

        $arr = [];
        if($request->hasFile('job_description_doc')) {
            $timestamp = now()->format('Y-m-d_H-i-s');
            $fileName = Str::slug($request->position, '_').'_'. $timestamp . '.' . $request->job_description_doc->extension();
            $request->job_description_doc->move(public_path('/job_vacancies/job_description_docs'), $fileName);
            $arr['job_description_doc_path'] = $fileName;
        }

        $vacancy->update(array_merge($request->all(), $arr));
        $vacancy->save();

        return back()->withFlashSuccess('Vacancy updated');
    }

    public function applications(Vacancy $vacancy)
    {
        $job_applications = JobApplication::where('vacancy_id', $vacancy->id)->get();
        return view('frontend.hr_service_now.job_vacancies_backend.applications', compact('job_applications', 'vacancy'));
    }

    public function destroy(Vacancy $vacancy)
    {
        $vacancy->delete();
        return redirect()->route('frontend.vacancies.backend.index')->withFlashInfo('Vacancy deleted successfully');
    }

    public function internalVacancyEmailPreview(Vacancy $vacancy)
    {
        $data['subject'] = "Internal Vacancy - ".$vacancy->position;
        $data['greeting'] = "Dear Colleagues,";
        $data['line'][] = "The following position is for placement. ".(isset($vacancy->location) ? "Position location is ".$vacancy->location."." : "");

        $data['formatted_line'][] = '
        <table style="direction: ltr; text-align: left; text-indent: 0px; width: 593.5pt; box-sizing: border-box; border-collapse: collapse; border-spacing: 0px; transform: scale(1); transform-origin: left top 0px;">
<tbody>
<tr>
<td style="direction: ltr; text-align: left; text-indent: 0px; border-width: 1pt; border-style: solid solid solid solid; border-color: initial; padding-right: 5.4pt; padding-left: 5.4pt; vertical-align: top; width: 33.35pt; height: 20.9pt;">
<div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)"><strong>S/N</strong></div>
</td>
<td style="direction: ltr; text-align: left; text-indent: 0px; border-width: 1pt; border-style: solid solid solid none; border-color: initial; padding-right: 5.4pt; padding-left: 5.4pt; vertical-align: top; width: 90.7pt; height: 20.9pt;">
<div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)"><strong>POSITION</strong></div>
</td>
<td style="direction: ltr; text-align: left; text-indent: 0px; border-width: 1pt; border-style: solid solid solid none; border-color: initial; padding-right: 5.4pt; padding-left: 5.4pt; vertical-align: top; width: 50.95pt; height: 20.9pt;">
<div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)"><strong>PROPOSED GRADE</strong></div>
</td>
<td style="direction: ltr; text-align: left; text-indent: 0px; border-width: 1pt; border-style: solid solid solid none; border-color: initial; padding-right: 5.4pt; padding-left: 5.4pt; vertical-align: top; width: 50.95pt; height: 20.9pt;">
<div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)"><strong>ELIGIBLE GRADE</strong></div>
</td>
<td style="direction: ltr; text-align: left; text-indent: 0px; border-width: 1pt; border-style: solid solid solid none; border-color: initial; vertical-align: top; width: 103.5pt; height: 20.9pt;">
<div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)"><strong>DATE ADVERTISED</strong></div>
</td>
<td style="direction: ltr; text-align: left; text-indent: 0px; border-width: 1pt; border-style: solid solid solid none; border-color: initial; padding-right: 5.4pt; padding-left: 5.4pt; vertical-align: top; width: 85.5pt; height: 20.9pt;">
<div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)"><strong>DATE OF CLOSING</strong></div>
</td>
<td style="direction: ltr; text-align: left; text-indent: 0px; border-width: 1pt; border-style: solid solid solid none; border-color: initial; padding-right: 5.4pt; padding-left: 5.4pt; vertical-align: top; width: 72pt; height: 20.9pt;">
<div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)"><strong>MODE OF SOURCING</strong></div>
</td>
<td style="direction: ltr; text-align: left; text-indent: 0px; border-width: 1pt; border-style: solid solid solid none; border-color: initial; padding-right: 5.4pt; padding-left: 5.4pt; vertical-align: top; width: 90pt; height: 20.9pt;">
<div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)"><strong>DEPARTMENT</strong></div>
</td>
<td style="direction: ltr; text-align: left; text-indent: 0px; border-width: 1pt; border-style: solid solid solid none; border-color: initial; vertical-align: top; width: 67.5pt; height: 20.9pt;">
<div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)"><strong>RECRUITER</strong></div>
</td>
</tr>
<tr>
<td style="direction: ltr; text-align: left; text-indent: 0px; border-width: 1pt; border-style: none solid solid; border-color: initial; padding-right: 5.4pt; padding-left: 5.4pt; vertical-align: top; width: 33.35pt; height: 20.9pt;">
<div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)">1</div>
</td>
<td style="direction: ltr; text-align: left; text-indent: 0px; border-right: 1pt solid; border-bottom: 1pt solid; padding-right: 5.4pt; padding-left: 5.4pt; vertical-align: top; width: 90.7pt; height: 20.9pt;">
<div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)">'.$vacancy->position.'</div>
</td>
<td style="direction: ltr; text-align: left; text-indent: 0px; border-right: 1pt solid; border-bottom: 1pt solid; padding-right: 5.4pt; padding-left: 5.4pt; vertical-align: top; width: 50.95pt; height: 20.9pt;">
<div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)">'.$vacancy->proposed_grade.'</div>
</td>
<td style="direction: ltr; text-align: left; text-indent: 0px; border-right: 1pt solid; border-bottom: 1pt solid; padding-right: 5.4pt; padding-left: 5.4pt; vertical-align: top; width: 50.95pt; height: 20.9pt;">
<div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)">'.$vacancy->eligible_grade.'</div>
</td>
<td style="direction: ltr; text-align: left; text-indent: 0px; border-right: 1pt solid; border-bottom: 1pt solid; vertical-align: top; width: 103.5pt; height: 20.9pt;">
<div class="x_elementToProof" style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)">'.$vacancy->date_advertised.'</div>
</td>
<td style="direction: ltr; text-align: left; text-indent: 0px; border-right: 1pt solid; border-bottom: 1pt solid; padding-right: 5.4pt; padding-left: 5.4pt; vertical-align: top; width: 85.5pt; height: 20.9pt;">
<div class="x_elementToProof" style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)">'.$vacancy->date_of_closing.'</div>
</td>
<td style="direction: ltr; text-align: left; text-indent: 0px; border-right: 1pt solid; border-bottom: 1pt solid; padding-right: 5.4pt; padding-left: 5.4pt; vertical-align: top; width: 72pt; height: 20.9pt;">
<div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)">'.$vacancy->mode_of_sourcing.'</div>
</td>
<td style="direction: ltr; text-align: left; text-indent: 0px; border-right: 1pt solid; border-bottom: 1pt solid; padding-right: 5.4pt; padding-left: 5.4pt; vertical-align: top; width: 90pt; height: 20.9pt;">
<div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)">'.$vacancy->department.'</div>
</td>
<td style="direction: ltr; text-align: left; text-indent: 0px; border-right: 1pt solid; border-bottom: 1pt solid; vertical-align: top; width: 67.5pt; height: 20.9pt;">
<div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)">'.$vacancy->recruiter.'</div>
</td>
</tr>
</tbody>
</table>
<p>&nbsp;</p>
        ';

        $data['formatted_line'][] = "Note that you must:";
        $data['formatted_line'][] = "<ul>
        <li>Meet the minimum requirements for the role.</li>
        <li>Must have spent more than 1 year in Arik Air.</li>
        <li>Inform your line manager before applying for this vacancy. </li>
        <li>Only Staff in grade levels 4 - 7 and staff in non-graduate roles that have obtained their degree (BSc /HND) are eligible to apply for this position.</li>
</ul>";
        $data['formatted_line'][] = "Please click on the button below to view the job description and apply.";
        $data['action_url'] = route('frontend.job_applications.show.vacancy', $vacancy->id);
        $data['action_text'] = "View Vacancy";
//        $data['to'] = $recipients['to'];
//        $data['cc'] = $recipients['cc'];
//        $data['to_name'] = 'Team';

//        $this->storeMessage($data, null);

        return view('mail.mail-template', compact('data'));
    }

    public function sendEmail(Request $request, Vacancy $vacancy)
    {
        $validated = $request->validate([
           'email_body' => 'required'
        ]);

        $vacancy->vacancy_email = $request->email_body;
        $vacancy->save();

        $msg = 'Email draft saved';
        if($request->send_email_input == 1 && ($request->filled('emails') || $request->filled('cc_emails') || $request->filled('bcc_emails'))) {
                            $data['subject'] = "Internal Vacancy - ".$vacancy->position;
                $data['greeting'] = "Dear Colleagues,";
                $data['line'][] = "The following position is for placement. ".(isset($vacancy->location) ? "Position location is ".$vacancy->location."." : "");

                $data['formatted_line'][] = '
        <table style="direction: ltr; text-align: left; text-indent: 0px; width: 593.5pt; box-sizing: border-box; border-collapse: collapse; border-spacing: 0px; transform: scale(1); transform-origin: left top 0px;">
<tbody>
<tr>
<td style="direction: ltr; text-align: left; text-indent: 0px; border-width: 1pt; border-style: solid solid solid solid; border-color: initial; padding-right: 5.4pt; padding-left: 5.4pt; vertical-align: top; width: 33.35pt; height: 20.9pt;">
<div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)"><strong>S/N</strong></div>
</td>
<td style="direction: ltr; text-align: left; text-indent: 0px; border-width: 1pt; border-style: solid solid solid none; border-color: initial; padding-right: 5.4pt; padding-left: 5.4pt; vertical-align: top; width: 90.7pt; height: 20.9pt;">
<div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)"><strong>POSITION</strong></div>
</td>
<td style="direction: ltr; text-align: left; text-indent: 0px; border-width: 1pt; border-style: solid solid solid none; border-color: initial; padding-right: 5.4pt; padding-left: 5.4pt; vertical-align: top; width: 50.95pt; height: 20.9pt;">
<div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)"><strong>PROPOSED GRADE</strong></div>
</td>
<td style="direction: ltr; text-align: left; text-indent: 0px; border-width: 1pt; border-style: solid solid solid none; border-color: initial; padding-right: 5.4pt; padding-left: 5.4pt; vertical-align: top; width: 50.95pt; height: 20.9pt;">
<div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)"><strong>ELIGIBLE GRADE</strong></div>
</td>
<td style="direction: ltr; text-align: left; text-indent: 0px; border-width: 1pt; border-style: solid solid solid none; border-color: initial; vertical-align: top; width: 103.5pt; height: 20.9pt;">
<div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)"><strong>DATE ADVERTISED</strong></div>
</td>
<td style="direction: ltr; text-align: left; text-indent: 0px; border-width: 1pt; border-style: solid solid solid none; border-color: initial; padding-right: 5.4pt; padding-left: 5.4pt; vertical-align: top; width: 85.5pt; height: 20.9pt;">
<div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)"><strong>DATE OF CLOSING</strong></div>
</td>
<td style="direction: ltr; text-align: left; text-indent: 0px; border-width: 1pt; border-style: solid solid solid none; border-color: initial; padding-right: 5.4pt; padding-left: 5.4pt; vertical-align: top; width: 72pt; height: 20.9pt;">
<div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)"><strong>MODE OF SOURCING</strong></div>
</td>
<td style="direction: ltr; text-align: left; text-indent: 0px; border-width: 1pt; border-style: solid solid solid none; border-color: initial; padding-right: 5.4pt; padding-left: 5.4pt; vertical-align: top; width: 90pt; height: 20.9pt;">
<div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)"><strong>DEPARTMENT</strong></div>
</td>
<td style="direction: ltr; text-align: left; text-indent: 0px; border-width: 1pt; border-style: solid solid solid none; border-color: initial; vertical-align: top; width: 67.5pt; height: 20.9pt;">
<div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)"><strong>RECRUITER</strong></div>
</td>
</tr>
<tr>
<td style="direction: ltr; text-align: left; text-indent: 0px; border-width: 1pt; border-style: none solid solid; border-color: initial; padding-right: 5.4pt; padding-left: 5.4pt; vertical-align: top; width: 33.35pt; height: 20.9pt;">
<div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)">1</div>
</td>
<td style="direction: ltr; text-align: left; text-indent: 0px; border-right: 1pt solid; border-bottom: 1pt solid; padding-right: 5.4pt; padding-left: 5.4pt; vertical-align: top; width: 90.7pt; height: 20.9pt;">
<div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)">'.$vacancy->position.'</div>
</td>
<td style="direction: ltr; text-align: left; text-indent: 0px; border-right: 1pt solid; border-bottom: 1pt solid; padding-right: 5.4pt; padding-left: 5.4pt; vertical-align: top; width: 50.95pt; height: 20.9pt;">
<div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)">'.$vacancy->proposed_grade.'</div>
</td>
<td style="direction: ltr; text-align: left; text-indent: 0px; border-right: 1pt solid; border-bottom: 1pt solid; padding-right: 5.4pt; padding-left: 5.4pt; vertical-align: top; width: 50.95pt; height: 20.9pt;">
<div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)">'.$vacancy->eligible_grade.'</div>
</td>
<td style="direction: ltr; text-align: left; text-indent: 0px; border-right: 1pt solid; border-bottom: 1pt solid; vertical-align: top; width: 103.5pt; height: 20.9pt;">
<div class="x_elementToProof" style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)">'.$vacancy->date_advertised->toDateString().'</div>
</td>
<td style="direction: ltr; text-align: left; text-indent: 0px; border-right: 1pt solid; border-bottom: 1pt solid; padding-right: 5.4pt; padding-left: 5.4pt; vertical-align: top; width: 85.5pt; height: 20.9pt;">
<div class="x_elementToProof" style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)">'.$vacancy->date_of_closing->toDateString().'</div>
</td>
<td style="direction: ltr; text-align: left; text-indent: 0px; border-right: 1pt solid; border-bottom: 1pt solid; padding-right: 5.4pt; padding-left: 5.4pt; vertical-align: top; width: 72pt; height: 20.9pt;">
<div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)">'.$vacancy->mode_of_sourcing.'</div>
</td>
<td style="direction: ltr; text-align: left; text-indent: 0px; border-right: 1pt solid; border-bottom: 1pt solid; padding-right: 5.4pt; padding-left: 5.4pt; vertical-align: top; width: 90pt; height: 20.9pt;">
<div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)">'.$vacancy->department.'</div>
</td>
<td style="direction: ltr; text-align: left; text-indent: 0px; border-right: 1pt solid; border-bottom: 1pt solid; vertical-align: top; width: 67.5pt; height: 20.9pt;">
<div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)">'.$vacancy->recruiter.'</div>
</td>
</tr>
</tbody>
</table>
<p>&nbsp;</p>
        ';

                $data['formatted_line'][] = $vacancy->vacancy_email;
                $data['formatted_line'][] = "Please click on the button below to view the job description and apply.";
                $data['action_url'] = route('frontend.job_applications.show.vacancy', $vacancy->id);
                $data['action_text'] = "View Vacancy";

            if ($request->filled('emails')) {
                $data['to'] = explode(',', (str_replace(' ', '', $request->emails)));
            }

            if ($request->filled('cc_emails')) {
                $data['cc'] = explode(',', (str_replace(' ', '', $request->cc_emails)));
            }

            if ($request->filled('bcc_emails')) {
                $data['bcc'] = explode(',', (str_replace(' ', '', $request->bcc_emails)));
            }

            $data['to_name'] = 'Team';
            $this->storeMessage($data, null);
            $msg = 'Email sent';
        }

        return back()->withFlashInfo($msg);
    }
}
