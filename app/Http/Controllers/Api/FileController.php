<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\OrgFile;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class FileController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(['message' => 'FileController']);
    }
    public function storeFile(Request $request)
    {
        try {
            $file = $request->file('file');
            $folder = "public/uploads/" . $request->folder;
            $size = $_FILES['file']['size'];
            $fpath = $_FILES['file']['name'];
            $extension = pathinfo($fpath, PATHINFO_EXTENSION);
            if ($size > 50000000) {
                return response()->json(['statusCode' => 201, 'message' => 'File too large', 'data' => []]);
            }
            if (!Storage::exists($folder)) {
                Storage::makeDirectory($folder);
            }
            if (str_contains($extension, "csv") || str_contains($extension, "CSV") || str_contains($extension, "xlsx") || str_contains($extension, "XLSX")) {
                $path = $file->storeAs(
                    $folder,
                    "temp" . "_" . rand(1, 1000)."_". $file->getClientOriginalName()
                );
            } else {
                $path = $file->storeAs(
                    $folder,
                    "temp" . "_" . $file->getClientOriginalName()
                );
            }
            $file_type = 0;
            $filePath = explode('public/uploads/', $path)[1];
            $filePathArr = explode('/', $filePath);
            $fileName = end($filePathArr);
            $originalFileName = explode("_", $fileName)[1];
            $originalFileName = rand(1, 1000) . "_" . explode(".", $originalFileName)[0];
            if (!empty($path)) {
                $ext = pathinfo(storage_path() . $path, PATHINFO_EXTENSION);
                if (str_contains($ext, "mp4") || str_contains($ext, "mov") || str_contains($ext, "MOV") || str_contains($ext, "HEVC") || str_contains($ext, "hevc")) {

                    $file_type = 2;
                    $inputPath = storage_path("app/" . $path);

                    $outputPath = storage_path("app/" . $folder . "/" . $originalFileName . "_thumbnail.jpg");

                    $inputPathEscaped = escapeshellarg($inputPath);
                    $outputPathEscaped = escapeshellarg($outputPath);
                    $command = "ffmpeg -i $inputPathEscaped -ss 00:00:01.000 -vframes 1 $outputPathEscaped";
                    exec($command, $output, $return_var);

                    if ($return_var == 0) {
                        $this->compressVideo($path, $folder, $fileName, $originalFileName);
                        $originalFileName = $originalFileName . ".mp4";
                    }
                } elseif (str_contains($ext, "jpg") || str_contains($ext, "jpeg") || str_contains($ext, "png")) {
                    $file_type = 1;
                    $this->compressImage($path, $folder, $fileName, $originalFileName);
                    $originalFileName = $originalFileName . ".jpg";
                } elseif (str_contains($ext, "heif") || str_contains($ext, "HEIF") || str_contains($ext, "heic") || str_contains($ext, "HEIC")) {
                    $file_type = 1;
                } else if(str_contains($ext, "gif") || str_contains($ext, "GIF") ){
                    $file_type = 10;
                }else if(str_contains($ext, "mp3") || str_contains($ext, "wav") || str_contains($ext, "aac") || str_contains($ext, "flac")){
                    $file_type = 11;
                }else {
                    $originalFileName = $fileName;
                }
            } else {
                $originalFileName = $fileName;
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
        return response()->json(['statusCode' => 200, 'message' => 'File uploaded successfully at ' . $path, 'data' => ["fileName" => $originalFileName, "folder" => $folder, "file_type" => $file_type]]);
    }


    public function deleteFile(Request $request)
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
        $isfolder = "/" . $org->storage_name . "/" . $folder;
        $orgFile = OrgFile::where('file_name', $file_name)->where('folder', $isfolder)->where('is_active', 1)->first();
        if (!empty($orgFile->id) && Storage::delete("public/" . $org->storage_name . "/" . $folder . "/" . $file_name)) {
            $orgFile->is_active = 0;
            $orgFile->updated_at = date("Y-m-d H:i:s");
            $orgFile->save();
        } else {
            $orgFile = OrgFile::where('file_name', $file_name)->where('folder', $isfolder)->first();
            if (!empty($orgFile->id)) {
                return response()->json(['message' => 'File has already been deleted']);
            } else {
                return response()->json(['message' => 'File deletion failed. File not present']);
            }
        }
        return response()->json(['message' => 'File deleted successfully']);
    }
    public function compressVideo($path, $folder, $fileName, $originalFileName)
    {
        try {
            set_time_limit(3600);
            $inputPath = storage_path("app/" . $path);
            $outputPath = storage_path("app/" . $folder . "/" . $originalFileName . ".mp4");
            $inputPathEscaped = escapeshellarg($inputPath);
            $outputPathEscaped = escapeshellarg($outputPath);
            $command = "ffmpeg -i $inputPathEscaped -preset veryfast -crf 28 -c:v libx264 -c:a copy $outputPathEscaped 2>&1";
            exec($command, $output, $return_var);
            unlink($inputPath);
            return response()->json(['message' => 'imageCompressor', "command" => $inputPath]);
        } catch (Exception $e) {
            Log::error("Exception: " . $e->getMessage());
            Log::error("Exception Trace: " . $e->getTraceAsString());
            return response()->json(['message' => $e->getMessage()]);
        }
    }
    public function compressImage($path, $folder, $fileName, $originalFileName)
    {
        try {
            try {
                $inputPath = storage_path("app/" . $path);
                $outputPath = storage_path("app/" . $folder . "/" . $originalFileName . ".jpg");
                $inputPathEscaped = escapeshellarg($inputPath);
                $outputPathEscaped = escapeshellarg($outputPath);
                $command = "ffmpeg -i $inputPathEscaped -vf scale=640:-1 -c:a copy $outputPathEscaped 2>&1";
                exec($command, $output, $return_var);
                unlink($inputPath);
                return response()->json(['message' => 'imageCompressor', "command" => $inputPath]);
            } catch (Exception $e) {
                print_r($e->getMessage());
            }
        } catch (Exception $e) {
            print_r($e->getMessage());
        }
    }

    public function compressAudio($path, $folder, $originalFileName)
    {
        try {
            set_time_limit(3600);
            $inputPath = storage_path("app/" . $path);
            $outputPath = storage_path("app/" . $folder . "/" . $originalFileName . ".mp3");
            $inputPathEscaped = escapeshellarg($inputPath);
            $outputPathEscaped = escapeshellarg($outputPath);
            $command = "ffmpeg -i $inputPathEscaped -b:a 128k $outputPathEscaped 2>&1";
            exec($command, $output, $return_var);
            unlink($inputPath);
            return response()->json(['message' => 'imageCompressor', "command" => $inputPath]);
        } catch (Exception $e) {
            Log::error("Exception: " . $e->getMessage());
            Log::error("Exception Trace: " . $e->getTraceAsString());
            return response()->json(['message' => $e->getMessage()]);
        }
    }
}
