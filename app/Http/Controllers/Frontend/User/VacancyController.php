<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\StaffMember;
use App\Models\Vacancy;
use Illuminate\Http\Request;

class VacancyController extends Controller
{
    public function index()
    {
        $vacancies = Vacancy::where('date_advertised', '<=', now())
            ->where('date_of_closing', '>=', now())
            ->latest()
            ->get();

        return view('frontend.hr_service_now.vacancies.listing')->with([
           'vacancies' => $vacancies
        ]);
    }

    public function show(Vacancy $vacancy)
    {
        $user = auth()->user();
        $staff_member = $user->staff_member;
        $email_preview = '';
        if($user->can('manage vacancy postings')) {
            if(isset($vacancy->vacancy_email)){
                $email_preview = $vacancy->vacancy_email;
            }else{
//                $data['subject'] = "Internal Vacancy - ".$vacancy->position;
//                $data['greeting'] = "Dear Colleagues,";
//                $data['line'][] = "The following position is for placement. ".(isset($vacancy->location) ? "Position location is ".$vacancy->location."." : "");

//                $data['formatted_line'][] = '
//        <table style="direction: ltr; text-align: left; text-indent: 0px; width: 593.5pt; box-sizing: border-box; border-collapse: collapse; border-spacing: 0px; transform: scale(1); transform-origin: left top 0px;">
//<tbody>
//<tr>
//<td style="direction: ltr; text-align: left; text-indent: 0px; border-width: 1pt; border-style: solid solid solid solid; border-color: initial; padding-right: 5.4pt; padding-left: 5.4pt; vertical-align: top; width: 33.35pt; height: 20.9pt;">
//<div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)"><strong>S/N</strong></div>
//</td>
//<td style="direction: ltr; text-align: left; text-indent: 0px; border-width: 1pt; border-style: solid solid solid none; border-color: initial; padding-right: 5.4pt; padding-left: 5.4pt; vertical-align: top; width: 90.7pt; height: 20.9pt;">
//<div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)"><strong>POSITION</strong></div>
//</td>
//<td style="direction: ltr; text-align: left; text-indent: 0px; border-width: 1pt; border-style: solid solid solid none; border-color: initial; padding-right: 5.4pt; padding-left: 5.4pt; vertical-align: top; width: 50.95pt; height: 20.9pt;">
//<div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)"><strong>GRADE RANGE</strong></div>
//</td>
//<td style="direction: ltr; text-align: left; text-indent: 0px; border-width: 1pt; border-style: solid solid solid none; border-color: initial; vertical-align: top; width: 103.5pt; height: 20.9pt;">
//<div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)"><strong>DATE ADVERTISED</strong></div>
//</td>
//<td style="direction: ltr; text-align: left; text-indent: 0px; border-width: 1pt; border-style: solid solid solid none; border-color: initial; padding-right: 5.4pt; padding-left: 5.4pt; vertical-align: top; width: 85.5pt; height: 20.9pt;">
//<div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)"><strong>DATE OF CLOSING</strong></div>
//</td>
//<td style="direction: ltr; text-align: left; text-indent: 0px; border-width: 1pt; border-style: solid solid solid none; border-color: initial; padding-right: 5.4pt; padding-left: 5.4pt; vertical-align: top; width: 72pt; height: 20.9pt;">
//<div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)"><strong>MODE OF SOURCING</strong></div>
//</td>
//<td style="direction: ltr; text-align: left; text-indent: 0px; border-width: 1pt; border-style: solid solid solid none; border-color: initial; padding-right: 5.4pt; padding-left: 5.4pt; vertical-align: top; width: 90pt; height: 20.9pt;">
//<div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)"><strong>DEPARTMENT</strong></div>
//</td>
//<td style="direction: ltr; text-align: left; text-indent: 0px; border-width: 1pt; border-style: solid solid solid none; border-color: initial; vertical-align: top; width: 67.5pt; height: 20.9pt;">
//<div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)"><strong>RECRUITER</strong></div>
//</td>
//</tr>
//<tr>
//<td style="direction: ltr; text-align: left; text-indent: 0px; border-width: 1pt; border-style: none solid solid; border-color: initial; padding-right: 5.4pt; padding-left: 5.4pt; vertical-align: top; width: 33.35pt; height: 20.9pt;">
//<div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)">1</div>
//</td>
//<td style="direction: ltr; text-align: left; text-indent: 0px; border-right: 1pt solid; border-bottom: 1pt solid; padding-right: 5.4pt; padding-left: 5.4pt; vertical-align: top; width: 90.7pt; height: 20.9pt;">
//<div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)">'.$vacancy->position.'</div>
//</td>
//<td style="direction: ltr; text-align: left; text-indent: 0px; border-right: 1pt solid; border-bottom: 1pt solid; padding-right: 5.4pt; padding-left: 5.4pt; vertical-align: top; width: 50.95pt; height: 20.9pt;">
//<div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)">'.$vacancy->eligible_grade.' - '.$vacancy->proposed_grade.'</div>
//</td>
//<td style="direction: ltr; text-align: left; text-indent: 0px; border-right: 1pt solid; border-bottom: 1pt solid; vertical-align: top; width: 103.5pt; height: 20.9pt;">
//<div class="x_elementToProof" style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)">'.$vacancy->date_advertised->toDateString().'</div>
//</td>
//<td style="direction: ltr; text-align: left; text-indent: 0px; border-right: 1pt solid; border-bottom: 1pt solid; padding-right: 5.4pt; padding-left: 5.4pt; vertical-align: top; width: 85.5pt; height: 20.9pt;">
//<div class="x_elementToProof" style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)">'.$vacancy->date_of_closing->toDateString().'</div>
//</td>
//<td style="direction: ltr; text-align: left; text-indent: 0px; border-right: 1pt solid; border-bottom: 1pt solid; padding-right: 5.4pt; padding-left: 5.4pt; vertical-align: top; width: 72pt; height: 20.9pt;">
//<div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)">'.$vacancy->mode_of_sourcing.'</div>
//</td>
//<td style="direction: ltr; text-align: left; text-indent: 0px; border-right: 1pt solid; border-bottom: 1pt solid; padding-right: 5.4pt; padding-left: 5.4pt; vertical-align: top; width: 90pt; height: 20.9pt;">
//<div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)">'.$vacancy->department.'</div>
//</td>
//<td style="direction: ltr; text-align: left; text-indent: 0px; border-right: 1pt solid; border-bottom: 1pt solid; vertical-align: top; width: 67.5pt; height: 20.9pt;">
//<div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)">'.$vacancy->recruiter.'</div>
//</td>
//</tr>
//</tbody>
//</table>
//<p>&nbsp;</p>
//        ';

//                $data['formatted_line'][] = "Note that you must:";
//                $data['formatted_line'][] = "Note that you must:<br>
//<ul>
//        <li>Meet the minimum requirements for the role.</li>
//        <li>Must have spent more than 1 year in Arik Air.</li>
//        <li>Inform your line manager before applying for this vacancy. </li>
//        <li>Only Staff in grade levels 4 - 7 and staff in non-graduate roles that have obtained their degree (BSc /HND) are eligible to apply for this position.</li>
//</ul>";
//                $data['formatted_line'][] = "Please click on the button below to view the job description and apply.";
//                $data['action_url'] = route('frontend.job_applications.show.vacancy', $vacancy->id);
//                $data['action_text'] = "View Vacancy";

                $email_preview = "Note that you must:<br>
<ul>
        <li>Meet the minimum requirements for the role.</li>
        <li>Must have spent more than 1 year in Arik Air.</li>
        <li>Inform your line manager before applying for this vacancy. </li>
        <li>Only Staff in grade levels 4 - 7 and staff in non-graduate roles that have obtained their degree (BSc /HND) are eligible to apply for this position.</li>
</ul>";
            }
        }

        $possible_line_managers = StaffMember::all();
        return view('frontend.hr_service_now.vacancies.show')->with([
           'vacancy' => $vacancy,
            'staff_member' => $staff_member,
            'possible_line_managers' => $possible_line_managers,
            'email_preview' => $email_preview
        ]);
    }
}
