<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\OrgFile;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class FileController extends Controller
{
    public function index(Request $request)
    {
        return view('file.index');
    }
    public function download(Request $request)
    {
        if (empty($request->token)) {
            return response()->json(['statusCode' => 201, 'message' => 'Missing Token', 'data' => []]);
        }
        $token = $request->token;
        $org = Organization::join('site_tokens as st', 'st.org_id', '=', 'organizations.id')->select('organizations.id as org_id', 'organizations.org_name as org_name', 'organizations.storage_name')->where('st.site_auth_token', $token)->where('st.token_is_active', 1)->first();
        if (empty($org->org_id)) {
            return response()->json(['statusCode' => 201, 'message' => 'Token Error', 'data' => []]);
        }
        $file_name = $request->file_name;
        $folder = $request->folder;
        return Storage::download("public/" . $org->storage_name . "/" . $folder . "/" . $file_name);
    }

    public function list(Request $request)
    {
        $user = Auth::user();
        $files = OrgFile::select(
            'org.org_name',
            'org_files.id',
            'org_files.file_name',
            'org_files.folder',
            'org_files.is_active',
            'org_files.file_type',
            'org_files.created_at',
            'org_files.updated_at'
        )
            ->join('organizations as org', 'org.id', '=', 'org_files.org_id')
            ->orderBy('org.id', 'desc');
        if ($user->user_role == 1) {
            $files = $files->where('org_files.org_id', $user->user_org);
        } else {
            $files = $files->where('org_files.org_id', $user->user_org);
        }
        $files = $files->get()
            ->groupBy('org.org_name')
            ->toArray();
        $result = [];
        foreach ($files as $key => $value) {
            foreach ($value as $file) {
                $orgName = $file['org_name'];
                unset($file['org_name']);
                $result[$orgName][] = $file;
            }
        }
        return view('file.list', ['files' => $result]);
    }

    public function deleteFile(Request $request, $id)
    {
        $user = Auth::user();
        $org = Organization::join('site_tokens as st', 'st.org_id', '=', 'organizations.id')
            ->select(
                'organizations.id as org_id',
                'organizations.org_name as org_name',
                'organizations.storage_name'
            )
            ->where('st.org_id', $user->user_org)
            ->where('st.token_is_active', 1)
            ->first();
        if (empty($org->org_id)) {
            return back()->with('error', 'Token Error');
        }
        $orgFile = OrgFile::where('id', $id)->where('is_active', 1)->first();
        if (!empty($orgFile->id) && Storage::delete("public" . $orgFile->folder . "/" . $orgFile->file_name)) {
            $orgFile->is_active = 0;
            $orgFile->updated_at = date("Y-m-d H:i:s");
            $orgFile->save();
        } else {
            $orgFile = OrgFile::where('id', $id)->first();
            if (!empty($orgFile->id)) {
                return back()->with('error', 'File has already been deleted');
            } else {
                return back()->with('error', 'File deletion failed.File not present');
            }
        }
        return back()->with('success', 'File Deleted Successfuly');
    }
}
