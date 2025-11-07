<?php

namespace App\Models\Auth\Traits\Relationship;

use App\Models\Auth\PasswordHistory;
use App\Models\Auth\SocialAccount;
use App\Models\BusinessArea;
use App\Models\ContactDetail;
use App\Models\FamilyDetail;
use App\Models\LegalTeamExternalLawyer;
use App\Models\ServiceNowGroupViewer;
use App\Models\SpiSector;
use App\Models\SpiSectorUserPermission;
use App\Models\StaffMember;

/**
 * Class UserRelationship.
 */
trait UserRelationship
{
    /**
     * @return mixed
     */
    public function providers()
    {
        return $this->hasMany(SocialAccount::class);
    }

    /**
     * @return mixed
     */
    public function passwordHistories()
    {
        return $this->hasMany(PasswordHistory::class);
    }

    public function staff_member()
    {
        return $this->hasOne(StaffMember::class, 'email', 'email');
    }

    public function contact_details()
    {
        return $this->hasMany(ContactDetail::class);
    }

    public function accessibleBusinessAreas()
    {
        $user = $this;
        $staff_ara_id = isset($user->staff_member) ? $user->staff_member->staff_ara_id : null;
        $business_areas = BusinessArea::all();
        $accessible_business_areas = [];
        $count = 1;


        foreach ($business_areas as $business_area) {
            if ($user->isAdmin() || $user->can('see all business score cards') || $staff_ara_id == $business_area->presenter_staff_ara_id || in_array($staff_ara_id, $business_area->co_presenters->pluck('staff_ara_id')->toArray())) {
                $accessible_business_areas[$count] = $business_area;
                $count++;
            }
        }

        return $accessible_business_areas;
    }

    public function spi_sectors()
    {
        if($this->isAdmin() || $this->can('manage safety performance index'))
            return SpiSector::pluck('id');

        return SpiSectorUserPermission::where('user_id', $this->id)->pluck('sector_id');
    }

    public function lawyer()
    {
        return $this->belongsTo(LegalTeamExternalLawyer::class,  'id', 'user_id');
    }

    public function serviceNowViewables()
    {
        return $this->hasMany(ServiceNowGroupViewer::class, 'staff_ara_id', 'staff_ara_id');
    }
}
