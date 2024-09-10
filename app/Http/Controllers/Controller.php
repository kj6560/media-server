<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

abstract class Controller
{
    public function storeLocalFile($file, $sfolder, $parentFolder)
    {
        try {

            $folder = $parentFolder . "/" . $sfolder;

            if (!Storage::exists($folder)) {
                Storage::makeDirectory($folder);
            }
            $path = $file->storeAs(
                $folder,
                "temp". "_" . $file->getClientOriginalName()
            );
            $file_type = 0;
            $filePath = explode($parentFolder, $path)[1];
            $filePathArr = explode('/', $filePath);
            $fileName = end($filePathArr);
            $originalFileName = explode("_", $fileName)[1];
            $originalFileName = rand(1, 1000)."_".explode(".", $originalFileName)[0];
            if (!empty($path)) {
                $ext = pathinfo(storage_path() . $path, PATHINFO_EXTENSION);
                if (str_contains($ext, "mp4") || str_contains($ext, "mov") || str_contains($ext, "MOV") || str_contains($ext, "HEVC") || str_contains($ext, "hevc")) {
                    $file_type = 2;
                    $inputPath = storage_path("app/" . $path);
                    $outputPath = storage_path("app/". $folder . "/" . $originalFileName . "_thumbnail.jpg");
                    $inputPathEscaped = escapeshellarg($inputPath);
                    $outputPathEscaped = escapeshellarg($outputPath);
                    $command = "ffmpeg -i $inputPathEscaped -ss 00:00:01.000 -vframes 1 $outputPathEscaped";
                    exec($command, $output, $return_var);
                    if ($return_var == 0) {
                        $outputPath = $this->compressVideo($path, $folder, $fileName, $originalFileName);
                        $originalFileName = $originalFileName.".mp4";
                    }
                } elseif (str_contains($ext, "jpg") || str_contains($ext, "jpeg") || str_contains($ext, "png")) {
                    $file_type = 1;
                    $outputPath = $this->compressImage($path, $folder, $fileName, $originalFileName);
                    $originalFileName = $originalFileName.".jpg";
                } elseif (str_contains($ext, "heif") || str_contains($ext, "HEIF") || str_contains($ext, "heic") || str_contains($ext, "HEIC")) {
                    $file_type = 1;
                    $outputPath = $fileName;
                }
                return $outputPath;
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
        return response()->json(['statusCode' => 200, 'message' => 'File uploaded successfully at ' . $path, 'data' => ["fileName" => $originalFileName, "folder" => $folder, "file_type" => $file_type]]);
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
            return $originalFileName . ".mp4";
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
                return $originalFileName . ".jpg";
            } catch (Exception $e) {
                print_r($e->getMessage());
            }
        } catch (Exception $e) {
            print_r($e->getMessage());
        }
    }

}
