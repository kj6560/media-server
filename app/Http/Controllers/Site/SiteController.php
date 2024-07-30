<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\User;
use DOMDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Streaming\FFMpeg;
use Streaming\Representation;

class SiteController extends Controller
{
    public function index(Request $request)
    {
        /* Compulsory start */
        // $config = [
        //     'ffmpeg.binaries'  => 'C:\ffmpeg\bin\ffmpeg.exe',
        //     'ffprobe.binaries' => 'C:\ffmpeg\bin\ffprobe.exe',
        //     'timeout'          => 3600, // The timeout for the underlying process
        //     'ffmpeg.threads'   => 12,   // The number of threads that FFmpeg should use
        // ];

        // $log = new Logger('FFmpeg_Streaming');
        // $log->pushHandler(new StreamHandler('C:\Users\Lenovo\Desktop\ffmpeg-streaming.log')); // path to log file

        // $ffmpeg = FFMpeg::create($config, $log);

        /* Compulsory End */


        // To upload and play from local
        // $video = $ffmpeg->open(storage_path("app/" . "public/org1/uploads/515_ad.mp4"));
        // $video->dash()
        //     ->setAdaption('id=0,streams=v id=1,streams=a') // Set the adaption.
        //     ->x264() // Format of the video. Alternatives: x264() and vp9()
        //     ->autoGenerateRepresentations() // Auto generate representations
        //     ->save(storage_path("app/" . "public/org1/mpd1/515_ad.mpd"));



        //To capture and play from camera
        // $capture = $ffmpeg->capture("Integrated Camera (5986:2130)");
        // $r_240p  = (new Representation)->setKiloBitrate(150)->setResize(426, 240);
        // $r_360p  = (new Representation)->setKiloBitrate(276)->setResize(640, 360);

        // $capture->dash()
        //     ->setAdaption('id=0,streams=v id=1,streams=a')
        //     ->x264()
        //     ->addRepresentations([$r_240p, $r_360p])
        //     ->save(storage_path("app/" . "public/org1/mpd/381_input.mpd"));
        $params = array (

            array (
              'start' => 8,
              'end' => 9,
            ),

            array (
              'start' => 16,
              'end' => 17,
            ),

            array (
              'start' => 22,
              'end' => 23,
            ),
        );
        return view('site.index',['params'=>$params]);
    }
    public function login(Request $request)
    {
        return view('site.login');
    }
    public function loginAuthentication(Request $request)
    {
        $data = $request->all();
        if (!empty($data) && $data['email'] && $data['password']) {
            $attemptData = array("email" => $data['email'], "password" => $data['password']);
            if (Auth::attempt($attemptData)) {
                $request->session()->regenerate();
                return redirect('/dashboard');
            } else {
                return back()->withErrors([
                    'errors' => 'user authentication failed.',
                ]);
            }
        } elseif (!empty($data) && $data['email']) {
            return back()->withErrors([
                'email' => 'plz enter valid email and password.',
            ])->onlyInput('email');
        }
    }
    public function register(Request $request)
    {
        return view('site.register');
    }
    public function createUser(Request $request)
    {
        $data = $request->all();
        if (!empty($data) && $data['email']) {

            $credentials = $request->validate([
                'name' => ['required', 'string'],
                'email' => ['required', 'email']
            ]);

            if ($credentials) {
                $user = User::where("email", $data['email'])->first();
                if (empty($user)) {
                    $password = bcrypt($data['password']);
                    $user = User::create([
                        'name' => $data['name'],
                        'email' => $data['email'],
                        'user_role' => 2,
                        'email_verified_at' => now(),
                        'password' => $password
                    ]);
                } else {
                    return redirect()->back()->with('error', 'Email already exists. Please login ');
                }

                if ($user) {
                    return redirect()->back()->with('success', 'You have been successfully registered');
                } else {
                    return back()->withErrors([
                        'email' => 'user already exists.',
                    ])->onlyInput('email');
                }
                if ($user) {
                    return redirect("login");
                }
            }
        } else {
            echo "not post";
        }
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function mergeWithAd(Request $request)
    {
        // Fetch the original MPD file and ad segments
        $mpdPath = "public/org1/mpd/381_input.mpd";

        $mpdContent = Storage::get($mpdPath);
        $mpdPathAd = "public/org1/mpd1/515_ad.mpd";
        $adMpdContent = Storage::get($mpdPathAd);

        // Insert ad segments into the main MPD content
        $modifiedMpdContent = $this->insertAdsIntoMpd($mpdContent, $adMpdContent);

        return response($modifiedMpdContent, 200)
            ->header('Content-Type', 'application/dash+xml');
    }
    private function insertAdsIntoMpd($mpdContent, $adMpdContent)
    {
        // Load the main MPD content
        $mpdDoc = new DOMDocument();
        $mpdDoc->loadXML($mpdContent);

        // Load the ad MPD content
        $adDoc = new DOMDocument();
        $adDoc->loadXML($adMpdContent);

        // Locate the <Period> elements
        $mpdPeriods = $mpdDoc->getElementsByTagName('Period');
        $adPeriods = $adDoc->getElementsByTagName('Period');

        // Create a new Period for the ads in the main MPD document
        foreach ($adPeriods as $adPeriod) {
            // Clone the ad period to import into the main MPD
            $importedAdPeriod = $mpdDoc->importNode($adPeriod, true);

            // Insert the ad period at the desired position (for example, after the first period)
            // You can modify the position as needed
            if ($mpdPeriods->length > 0) {
                $firstPeriod = $mpdPeriods->item(0);
                $firstPeriod->parentNode->insertBefore($importedAdPeriod, $firstPeriod->nextSibling);
            } else {
                // If there are no periods, append the ad period to the MPD
                $mpdDoc->documentElement->appendChild($importedAdPeriod);
            }
        }

        // Save the modified MPD content as a string
        $modifiedMpdContent = $mpdDoc->saveXML();

        return $modifiedMpdContent;
    }
}
