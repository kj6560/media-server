<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function index(Request $request){
        $orgs = Organization::all();
        return view('organizations.index',['orgs'=>$orgs]);
    }
    public function createOrganization(Request $request){
        $orgs = Organization::all();
        return view('organizations.createOrganization',['orgs'=>$orgs]);
    }
    public function store(Request $request){
        $data = $request->all();
        unset($data['_token']);
        if(!empty($data['id'])) {
            $org = Organization::find($data['id']);
        } else {
            $org = new Organization();

        }
        $org->org_name = $data['org_name'];
        $org->org_description = $data['org_description'];
        $org->org_address = $data['org_address'];
        $org->org_contact_name = $data['org_contact_name'];
        $org->org_contact_email = $data['org_contact_email'];
        $org->org_contact_number = $data['org_contact_number'];
        $org->org_type = $data['org_type'];
        $org->org_is_active = $data['org_is_active'];
        $org->storage_name = $data['storage_name'];
        $org->parent_id = $data['parent_id'];
        $file_logo = $request->file('logo');
        if(!empty($file_logo)) {
            $file_logo_resp = $this->storeLocalFile($file_logo, "organizations","public");
            if(!empty($file_logo_resp)) {
                $org->logo = $file_logo_resp;
            }
        }
        $file_org_banner = $request->file('org_banner');
        if(!empty($file_org_banner)) {
            $file_org_banner_resp = $this->storeLocalFile($file_org_banner, "organizations","public");
            if(!empty($file_org_banner_resp)) {
                $org->org_banner = $file_org_banner_resp;
            }
        }
        if($org->save()) {
            return redirect()->back()->with('success', 'Organization created successfully');
        }else{
            return redirect()->back()->with('error', 'Organization creation failed');
        }
    }
    public function edit(Request $request,$id){
        $org = Organization::find($id);
        $orgs = Organization::all();
        return view('organizations.createOrganization', ['org'=>$org, 'orgs'=>$orgs]);
    }
    public function delete(Request $request, $id){
        $org = Organization::find($id);
        if($org->delete()) {
            return redirect()->back()->with('success', 'Organization deleted successfully');
        }else{
            return redirect()->back()->with('error', 'Organization deletion failed');
        }
    }
}
