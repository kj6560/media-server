<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\SiteToken;
use Illuminate\Http\Request;

class SiteTokenController extends Controller
{
    public function index(Request $request){
        $sites = SiteToken::
        join('organizations as org','org.id','=','site_tokens.org_id')
        ->select('site_tokens.*', 'org.org_name','org.org_contact_name')
        ->get();
        return view('site_token.index',['sites'=>$sites]);
    }
    public function create(Request $request){
        $orgs = Organization::all();
        return view('site_token.create',['orgs'=>$orgs]);
    }
    public function edit(Request $request, $id){
        $orgs = Organization::all();
        $site = SiteToken::find($id);
        return view('site_token.create', ['orgs'=>$orgs, 'site'=>$site]);
    }
    public function store(Request $request){
        $data = $request->all();
        
        if(!empty($data['id'])){
            $site = SiteToken::find($data['id']);
        }else{
            $site = new SiteToken();
        }
        $site->org_id = $request->org_id;
        if(!empty($request->site_auth_token))
            $site->site_auth_token = bcrypt($request->site_auth_token);
        $site->token_is_active = $request->token_is_active;
        $site->save();
        return redirect()->route('site_token.index')->with('success', 'Site Token created successfully.');
    }
    public function delete(Request $request, $id){
        $site = SiteToken::find($id);
        $site->delete();
        return redirect()->route('site_token.index')->with('success', 'Site Token deleted successfully.');
    }
}
