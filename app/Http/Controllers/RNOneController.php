<?php

namespace App\Http\Controllers;

use App\Models\Auth\User;
use App\Models\StaffMember;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class RNOneController extends Controller
{

    public function updatePermanentStaff()
    {
        $now = now();
        $staff_info = DB::table('permanent_staff_members')->get();
        $staff_members = DB::table('staff_member_details')
            ->get();
        foreach($staff_info as $info){
            $counter[$info->staff_id] = $staff_members->where('staff_id', $info->staff_id)
                ->count();

            $staffMember = StaffMember::where('staff_id', $info->staff_id)->first();
            if(!$staffMember) {
                $staffMember = new StaffMember();
                $staffMember->staff_id = $info->staff_id;
                $staffMember->staff_ara_id = substr($info->staff_id, 3);
                $staffMember->surname = $info->surname;
                $staffMember->other_names = $info->other_names;
                echo 'created: ' . $info->staff_id . ' ** ';
            }
            $staffMember->department_name = $info->department_name;
            $staffMember->job_title = $info->job_title;
            $staffMember->location = $info->location;
            $staffMember->employment_category = 'full staff';
            $staffMember->deleted_at = null;
            $staffMember->save();
            echo 'updated: s_n '.$info->s_n.' - '.$info->staff_id.' <br>';

            $staffAraIds[] = $info->staff_id;
        }

        $now = now();
        $nonC = StaffMember::
        whereNotIn('staff_id', $staffAraIds)->get();

        foreach($nonC as $nnc) {
            $nnc->deleted_at = $now;
            $nnc->save();
        }
    }

    public function updateContractTempStaff()
    {
        $now = now();
        $new_info = DB::table('contract_staff_members')->get();
        foreach($new_info as $info){

            $staffMember = StaffMember::where('staff_ara_id', $info->staff_id)->first();
            if(!$staffMember) {
                $staffMember = new StaffMember();
                $staffMember->staff_id = $info->staff_id;
                $staffMember->staff_ara_id = substr($info->staff_id, 3);
                echo 'created: ' . $staffMember->staff_ara_id . ' ** ';
            }
            $staffMember->other_names = $info->names;
            $staffMember->department_name = $info->department_name;
            $staffMember->job_title = $info->job_title;
            $staffMember->location = $info->location;
            $staffMember->employment_category = $info->staff_category;
            $staffMember->updated_at = $now;
            $staffMember->deleted_at = null;
            $staffMember->save();


            echo 'updated: ' . $info->staff_id . ' <br>';

            $staffAraIds[] = $info->staff_id;
        }
        return $staffAraIds;
    }

    public function sortIDcards()
    {
        $staff_no_id_cards = StaffMember::whereNull('id_card_file_name')->get();
        foreach($staff_no_id_cards as $staff){
            $id_card = DB::table('id_card_files')
                ->where('ara_id', $staff->staff_ara_id)
                ->first();

            if($id_card && $id_card->extension_name != '.pdf'){
                $staff->id_card_file_name = $id_card->id_file_name;
                $staff->save();
                echo $staff->staff_ara_id.' - '.$id_card->extension_name.'<br>';
            }
        }
    }

    public function addPilotUsers()
    {
        $pilots = [
            [
                'staff_id' => '',
                'surname' => 'TEST',
                'other_names' => 'AVANTI',
                'fleet' => 'B737',
                'email' => 'avanti@arikair.com'
            ],
            [
                'staff_id' => 'ARA2028',
                'surname' => 'MUSA',
                'other_names' => 'AHMED',
                'fleet' => 'B737',
                'email' => 'ahmed.musa@arikair.com'
            ],
            [
                'staff_id' => 'ARA2382',
                'surname' => 'ADU-AWUAH',
                'other_names' => 'EBENEZER',
                'fleet' => 'B737',
                'email' => 'ebenezer.adu-awuah@arikair.com'
            ],
            [
                'staff_id' => 'ARA2407',
                'surname' => 'ANYANWU',
                'other_names' => 'SAMUEL',
                'fleet' => 'B737',
                'email' => 'samuel.anyanwu@arikair.com'
            ],
            [
                'staff_id' => 'ARA2412',
                'surname' => 'YAHAYA',
                'other_names' => 'KAMALU',
                'fleet' => 'B737',
                'email' => 'kamalu.yahaya@arikair.com'
            ],
            [
                'staff_id' => 'ARA2415',
                'surname' => 'GAYA',
                'other_names' => 'KHALIL',
                'fleet' => 'B737',
                'email' => 'khalil.gaya@arikair.com'
            ],
            [
                'staff_id' => 'ARA2422',
                'surname' => 'YAKUBU',
                'other_names' => 'MOHAMMED',
                'fleet' => 'B737',
                'email' => 'mohammed.yakubu@arikair.com'
            ],
            [
                'staff_id' => 'ARA2452',
                'surname' => 'SHUAIBU',
                'other_names' => 'ABDULHAFIZ',
                'fleet' => 'B737',
                'email' => 'abdulhafiz.shuaibu@arikair.com'
            ],
            [
                'staff_id' => 'ARA2830',
                'surname' => 'OGOYI',
                'other_names' => 'OLURANTI OMOTOYOSI',
                'fleet' => 'B737',
                'email' => 'oluranti.ogunwale@arikair.com'
            ],
            [
                'staff_id' => 'ARA5349',
                'surname' => 'SHITTU',
                'other_names' => 'JOSHUA OLABODE',
                'fleet' => 'B737',
                'email' => 'joshua.shittu@arikair.com'
            ],
            [
                'staff_id' => 'ARA2200',
                'surname' => 'JAMES',
                'other_names' => 'BERTRAM NIGEL',
                'fleet' => 'Q400',
                'email' => 'nigel.james@arikair.com'
            ],
            [
                'staff_id' => 'ARA3141',
                'surname' => 'OKONYE',
                'other_names' => 'CHIKE ANDREW',
                'fleet' => 'Q400',
                'email' => 'chike.okonye@arikair.com'
            ]
        ];

        $fleets_permission['Q400'] = Permission::findByName('view Q400 PDFs');
        $fleets_permission['B737'] = Permission::findByName('view 737 PDFs');
        $now = now();
        foreach($pilots as $pilot){
            $user = User::where('email', $pilot['email'])->first();
            if(!$user){
                $user = new User();
                $user->email = $pilot['email'];
                $user->first_name = $pilot['other_names'];
                $user->last_name = $pilot['surname'];
                $user->active = 1;
                $user->confirmed = 1;
                $user->password = password_hash($user->email, PASSWORD_BCRYPT);
                $user->created_at = $now;
                $user->updated_at = $now;
                $user->save();
                echo 'created user: '.$pilot['email'].'<br>';
            }
            $user->givePermissionTo($fleets_permission[$pilot['fleet']]);
            echo 'permitted user: '.$pilot['email'].' for '.$fleets_permission[$pilot['fleet']]->name.'<br>***<br><br>';
        }
    }


    public function setShiftStatus()
    {
        // October 10, 2023, 3:25pm
        $shift_list = DB::table('staff_shift_and_schedule')
            ->whereNull('shift_sorted')
            ->get();
        // shift_sorted, remote_sorted
        $shift_sorted = [];

        foreach($shift_list as $list_item){
            $staff_member = StaffMember::where('staff_ara_id', $list_item->staff_ara_id)->first();
            if($staff_member){
                $staff_member->shift_nonshift = $list_item->shiftnonshift;
                $staff_member->save();
                $shift_sorted[] = $list_item->id;
                echo $list_item->staff_ara_id.'<br>';
            }
        }

        $now = now();
        DB::table('staff_shift_and_schedule')
            ->whereIn('id', $shift_sorted)
            ->update([
                'shift_sorted' => $now
            ]);
    }

    public function bulkDeactivationInitiation()
    {
        $staffLeavers = array(
            array(
                'ID_NO' => 'ARA6474',
                'SURNAME' => 'UKPANAH',
                'FIRST_NAME' => 'UKEME',
                'EFFECTIVE_DATE' => '5-Oct-23'
            ),
            array(
                'ID_NO' => 'ARA3500',
                'SURNAME' => 'NDINWA',
                'FIRST_NAME' => 'NICK KENECHUKWU',
                'EFFECTIVE_DATE' => '9-Oct-23'
            ),
            array(
                'ID_NO' => 'ARA2426',
                'SURNAME' => 'OLATINWO',
                'FIRST_NAME' => 'ADEDAYO ABIODUN',
                'EFFECTIVE_DATE' => '11-Oct-23'
            ),
            array(
                'ID_NO' => 'ARA4716',
                'SURNAME' => 'ADEOTI',
                'FIRST_NAME' => 'OLUWAFEMI DAVID',
                'EFFECTIVE_DATE' => '12-Oct-23'
            ),
            array(
                'ID_NO' => 'ARA4614',
                'SURNAME' => 'ADEJUWON',
                'FIRST_NAME' => 'FESTUS',
                'EFFECTIVE_DATE' => '25-Oct-23'
            ),
            array(
                'ID_NO' => 'ARA4849',
                'SURNAME' => 'BUKAR',
                'FIRST_NAME' => 'HAMZA',
                'EFFECTIVE_DATE' => '26-Oct-23'
            ),
            array(
                'ID_NO' => 'ARA4563',
                'SURNAME' => 'POPOOLA',
                'FIRST_NAME' => 'AHMED',
                'EFFECTIVE_DATE' => '26-Oct-23'
            ),
            array(
                'ID_NO' => 'ARA4564',
                'SURNAME' => 'SHOKUNBI',
                'FIRST_NAME' => 'KEHINDE',
                'EFFECTIVE_DATE' => '26-Oct-23'
            ),
            array(
                'ID_NO' => 'ARA4579',
                'SURNAME' => 'AJAO',
                'FIRST_NAME' => 'MUYIDEEN',
                'EFFECTIVE_DATE' => '26-Oct-23'
            ),
            array(
                'ID_NO' => 'ARA4580',
                'SURNAME' => 'IBRAHIM',
                'FIRST_NAME' => 'WAHEED',
                'EFFECTIVE_DATE' => '26-Oct-23'
            ),
            array(
                'ID_NO' => 'ARA7119',
                'SURNAME' => 'AKENI',
                'FIRST_NAME' => 'JANET',
                'EFFECTIVE_DATE' => '26-Oct-23'
            ),
            array(
                'ID_NO' => 'ARA2355',
                'SURNAME' => 'ROEPKE',
                'FIRST_NAME' => 'ROLF',
                'EFFECTIVE_DATE' => '4-Nov-23'
            ),
            array(
                'ID_NO' => 'ARA4720',
                'SURNAME' => 'EMEKA-OFOMA',
                'FIRST_NAME' => 'CHIBUZOR',
                'EFFECTIVE_DATE' => '6-Nov-23'
            ),
            array(
                'ID_NO' => 'ARA5244',
                'SURNAME' => 'BODUNDE',
                'FIRST_NAME' => 'LAWRENCE',
                'EFFECTIVE_DATE' => '11-Nov-23'
            ),
            array(
                'ID_NO' => 'ARA3416',
                'SURNAME' => 'TANKO',
                'FIRST_NAME' => 'EMMANUEL JOSEPH',
                'EFFECTIVE_DATE' => '29-Nov-23'
            ),
            array(
                'ID_NO' => 'ARA4281',
                'SURNAME' => 'HASSAN',
                'FIRST_NAME' => 'ADAMU',
                'EFFECTIVE_DATE' => '5-Dec-23'
            ),
            array(
                'ID_NO' => 'ARA7219',
                'SURNAME' => 'MUHAMMAD-TUKUR',
                'FIRST_NAME' => 'SALIHU',
                'EFFECTIVE_DATE' => '10-Dec-23'
            ),
            array(
                'ID_NO' => 'ARA7162',
                'SURNAME' => 'ISA',
                'FIRST_NAME' => 'BELLO',
                'EFFECTIVE_DATE' => '20-Dec-23'
            ),
            array(
                'ID_NO' => 'ARA6745',
                'SURNAME' => 'HAMMED',
                'FIRST_NAME' => 'AMINAT TOPE',
                'EFFECTIVE_DATE' => '29-Dec-23'
            ),
            array(
                'ID_NO' => 'ARA4013',
                'SURNAME' => 'MUSA',
                'FIRST_NAME' => 'YUSSUF',
                'EFFECTIVE_DATE' => '31-Dec-23'
            ),
            array(
                'ID_NO' => 'ARA4190',
                'SURNAME' => 'ETUKUDOH',
                'FIRST_NAME' => 'ISAAC',
                'EFFECTIVE_DATE' => '31-Dec-23'
            ),
            array(
                'ID_NO' => 'ARA7353',
                'SURNAME' => 'ADEGBOLA',
                'FIRST_NAME' => 'JANET',
                'EFFECTIVE_DATE' => '31-Dec-23'
            ),
            array(
                'ID_NO' => 'ARA3228',
                'SURNAME' => 'NWOSU',
                'FIRST_NAME' => 'NNENNA',
                'EFFECTIVE_DATE' => '31-Dec-23'
            ),
            array(
                'ID_NO' => 'ARA6362',
                'SURNAME' => 'AWOLOWO',
                'FIRST_NAME' => 'OLUYOMI',
                'EFFECTIVE_DATE' => '31-Dec-23'
            ),
            array(
                'ID_NO' => 'ARA4463',
                'SURNAME' => 'DAZEL',
                'FIRST_NAME' => 'NANKLING SAMUEL',
                'EFFECTIVE_DATE' => '2-Jan-24'
            ),
            array(
                'ID_NO' => 'ARA5472',
                'SURNAME' => 'GARBA',
                'FIRST_NAME' => 'IBRAHIM',
                'EFFECTIVE_DATE' => '6-Jan-24'
            ),
            array(
                'ID_NO' => 'ARA2397',
                'SURNAME' => 'FROSS',
                'FIRST_NAME' => 'STEPHEN',
                'EFFECTIVE_DATE' => '26-Jan-24'
            ),
            array(
                'ID_NO' => 'ARA7820',
                'SURNAME' => 'OBI',
                'FIRST_NAME' => 'OBIAGELI CHINENYE',
                'EFFECTIVE_DATE' => '31-Jan-24'
            ),
            array(
                'ID_NO' => 'ARA5335',
                'SURNAME' => 'EKERE',
                'FIRST_NAME' => 'ANTHONY',
                'EFFECTIVE_DATE' => '31-Jan-24'
            ),
            array(
                'ID_NO' => 'ARA5600',
                'SURNAME' => 'IDOKO',
                'FIRST_NAME' => 'MATHIAS AHWUNYE',
                'EFFECTIVE_DATE' => '2-Feb-24'
            ),
            array(
                'ID_NO' => 'ARA4790',
                'SURNAME' => 'LASAKI',
                'FIRST_NAME' => 'OLASUBOMI ADERAYO',
                'EFFECTIVE_DATE' => '2-Feb-24'
            ),
            array(
                'ID_NO' => 'ARA7020',
                'SURNAME' => 'FAKEYE',
                'FIRST_NAME' => 'OLAYINKA',
                'EFFECTIVE_DATE' => '15-Feb-24'
            ),
            array(
                'ID_NO' => 'ARA2103',
                'SURNAME' => 'AMAECHI',
                'FIRST_NAME' => 'EMMANUEL CHUKWUDI',
                'EFFECTIVE_DATE' => '15-Feb-24'
            ),
            array(
                'ID_NO' => 'ARA2440',
                'SURNAME' => 'TUKUR',
                'FIRST_NAME' => 'MUHAMMAD',
                'EFFECTIVE_DATE' => '16-Feb-24'
            ),
            array(
                'ID_NO' => 'ARA2416',
                'SURNAME' => 'IBRAHIM',
                'FIRST_NAME' => 'OLUFEMI',
                'EFFECTIVE_DATE' => '18-Feb-24'
            ),
            array(
                'ID_NO' => 'ARA7144',
                'SURNAME' => 'GEORGE',
                'FIRST_NAME' => 'EZINWANNE',
                'EFFECTIVE_DATE' => '18-Feb-24'
            ),
            array(
                'ID_NO' => 'ARA2822',
                'SURNAME' => 'AFIWAJOYE',
                'FIRST_NAME' => 'PETER OLALEKAN',
                'EFFECTIVE_DATE' => '29-Feb-24'
            ),
            array(
                'ID_NO' => 'ARA7857',
                'SURNAME' => 'ARIGO',
                'FIRST_NAME' => 'MIRIAM',
                'EFFECTIVE_DATE' => '29-Feb-24'
            )
        );

        // Prepare an array to hold staff IDs and their effective dates
        $staffUpdates = [];

        foreach ($staffLeavers as $staffLeaver) {
            $staffId = $staffLeaver['ID_NO'];
            $effectiveDate = date('Y-m-d', strtotime($staffLeaver['EFFECTIVE_DATE']));

            // Store staff ID and effective date in the updates array
            $staffUpdates[$staffId] = [
                'resigned_on' => $effectiveDate,
                'restrict_access_from' => $effectiveDate,
                'deactivate_from' => $effectiveDate,
                'deactivated_at' => now(), // Assuming deactivated_at should be set to current timestamp
            ];
        }

        // Update staff member details in a single query
        foreach ($staffUpdates as $staffId => $updates) {
            StaffMember::where('staff_id', $staffId)->update($updates);
        }

        return response()->json(['message' => 'Staff details updated successfully', 'staff_leavers' => $staffLeavers]);
    }
}
