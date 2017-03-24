<?php namespace Interview;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, Response, Mail, URL, Queue;
use User as UserModel;
use Job as JobModel;
use Company as CompanyModel;
use CompanyVICreated as CompanyVICreatedModel;
use CompanyVIResponse as CompanyVIResponseModel;
use Email as EmailModel;
use FaceInterview as FaceInterviewModel;

class InterviewController extends \BaseController {
	
    public function videoInterviewStepOne($userSlug, $companySlug) {

        if (Input::has('_token')) {
            $user = UserModel::findBySlug($userSlug);
            $company = CompanyModel::findBySlug($companySlug);
            $token = Input::get('_token');

            if (CompanyVICreatedModel::where('user_id', $user->id)->where('company_id', $company->id)->where('token', $token)->get()->count() > 0) {
                $viCreated = CompanyVICreatedModel::where('user_id', $user->id)->where('company_id', $company->id)->where('token', $token)->firstOrFail();

                $today = date('Y-m-d');

                if ($viCreated->expire_at < $today) {
                    return View::make('404.interviewExpired');
                }else {
                    $param['user'] = $user;
                    $param['company'] = $company;
                    $param['viCreated'] = $viCreated;

                    return View::make('interview.video.stepOne')->with($param);
                }
            }else {
                return View::make('404.index');
            }


        }else {
            return View::make('404.index');
        }
    }

    public function videoInterviewStepTwo($userSlug, $companySlug) {

        if (Input::has('_token')) {
            $user = UserModel::findBySlug($userSlug);
            $company = CompanyModel::findBySlug($companySlug);
            $token = Input::get('_token');

            if (CompanyVICreatedModel::where('user_id', $user->id)->where('company_id', $company->id)->where('token', $token)->get()->count() > 0) {
                $viCreated = CompanyVICreatedModel::where('user_id', $user->id)->where('company_id', $company->id)->where('token', $token)->firstOrFail();

                $today = date('Y-m-d');

                if ($viCreated->expire_at < $today) {
                    return View::make('404.interviewExpired');
                }else {
                    $param['user'] = $user;
                    $param['company'] = $company;
                    $param['viCreated'] = $viCreated;

                    return View::make('interview.video.stepTwo')->with($param);
                }
            }else {
                return View::make('404.index');
            }


        }else {
            return View::make('404.index');
        }
    }

    public function videoInterviewStepThree($userSlug, $companySlug) {

        if (Input::has('_token')) {
            $user = UserModel::findBySlug($userSlug);
            $company = CompanyModel::findBySlug($companySlug);
            $token = Input::get('_token');

            if (CompanyVICreatedModel::where('user_id', $user->id)->where('company_id', $company->id)->where('token', $token)->get()->count() > 0) {
                $viCreated = CompanyVICreatedModel::where('user_id', $user->id)->where('company_id', $company->id)->where('token', $token)->firstOrFail();

                $today = date('Y-m-d');

                if ($viCreated->expire_at < $today) {
                    return View::make('404.interviewExpired');
                }else {
                    $param['user'] = $user;
                    $param['company'] = $company;
                    $param['viCreated'] = $viCreated;
                    $param['questions'] = $viCreated->questionnaire->questions;

                    return View::make('interview.video.stepThree')->with($param);
                }
            }else {
                return View::make('404.index');
            }


        }else {
            return View::make('404.index');
        }
    }


    public function videoInterviewStepFour($userSlug, $companySlug) {

        if (Input::has('_token')) {
            $user = UserModel::findBySlug($userSlug);
            $company = CompanyModel::findBySlug($companySlug);
            $token = Input::get('_token');

            if (CompanyVICreatedModel::where('user_id', $user->id)->where('company_id', $company->id)->where('token', $token)->get()->count() > 0) {
                $viCreated = CompanyVICreatedModel::where('user_id', $user->id)->where('company_id', $company->id)->where('token', $token)->firstOrFail();

                $today = date('Y-m-d');

                if ($viCreated->expire_at < $today) {
                    return View::make('404.interviewExpired');
                }else {
                    $param['user'] = $user;
                    $param['company'] = $company;
                    $param['viCreated'] = $viCreated;

                    return View::make('interview.video.stepFour')->with($param);
                }
            }else {
                return View::make('404.index');
            }


        }else {
            return View::make('404.index');
        }
    }


    public function videoSave() {

        $OSList = array
        (
            'Windows 3.11' => 'Win16',
            'Windows 95' => '(Windows 95)|(Win95)|(Windows_95)',
            'Windows 98' => '(Windows 98)|(Win98)',
            'Windows 2000' => '(Windows NT 5.0)|(Windows 2000)',
            'Windows XP' => '(Windows NT 5.1)|(Windows XP)',
            'Windows Server 2003' => '(Windows NT 5.2)',
            'Windows Vista' => '(Windows NT 6.0)',
            'Windows 7' => '(Windows NT 7.0)',
            'Windows NT 4.0' => '(Windows NT 4.0)|(WinNT4.0)|(WinNT)|(Windows NT)',
            'Windows ME' => 'Windows ME',
            'Open BSD' => 'OpenBSD',
            'Sun OS' => 'SunOS',
            'Linux' => '(Linux)|(X11)',
            'Mac OS' => '(Mac_PowerPC)|(Macintosh)',
            'QNX' => 'QNX',
            'BeOS' => 'BeOS',
            'OS/2' => 'OS/2',
            'Search Bot'=>'(nuhk)|(Googlebot)|(Yammybot)|(Openbot)|(Slurp)|(MSNBot)|(Ask Jeeves/Teoma)|(ia_archiver)'
        );
        // Loop through the array of user agents and matching operating systems
        foreach($OSList as $CurrOS=>$Match)
        {
            // Find a match
            if (strpos($Match, $_SERVER['HTTP_USER_AGENT']) !== false)
            {
                // We found the correct match
                break;
            }
        }

        // if it is audio-blob
        if (isset($_FILES["audio-blob"])) {
            $uploadDirectory = ABS_VIDEO_PATH.$_POST["filename"].'.wav';
            if (!move_uploaded_file($_FILES["audio-blob"]["tmp_name"], $uploadDirectory)) {
                echo("Problem writing audio file to disk!");
            }
            else {
                // if it is video-blob
                if (isset($_FILES["video-blob"])) {
                    $uploadDirectory = ABS_VIDEO_PATH.$_POST["filename"].'.webm';
                    if (!move_uploaded_file($_FILES["video-blob"]["tmp_name"], $uploadDirectory)) {
                        echo("Problem writing video file to disk!");
                    }
                    else {
                        $audioFile = ABS_VIDEO_PATH.$_POST["filename"].'.wav';
                        $videoFile = ABS_VIDEO_PATH.$_POST["filename"].'.webm';
                        $mergedFile = ABS_VIDEO_PATH.$_POST["filename"].'-merged.webm';

                        // ffmpeg depends on yasm
                        // libvpx depends on libvorbis
                        // libvorbis depends on libogg
                        // make sure that you're using newest ffmpeg version!

                        if(!strrpos($CurrOS, "Windows")) {
                            $cmd = '-i '.$audioFile.' -i '.$videoFile.' -map 0:0 -map 1:0 '.$mergedFile;
                        }
                        else {
                            $cmd = ' -i '.$audioFile.' -i '.$videoFile.' -c:v mpeg4 -c:a vorbis -b:v 64k -b:a 35000 -strict experimental '.$mergedFile;
                        }

                        exec('ffmpeg '.$cmd.' 2>&1', $out, $ret);
                        //exec('ffmpeg -i /opt/lampp/htdocs/stage/public/assets/videos/142062818.wav -i /opt/lampp/htdocs/stage/public/assets/videos/142062818.webm -map 0:0 -map 1:0 /opt/lampp/htdocs/stage/public/assets/videos/142062818-merged.webm');
                        if ($ret){
                            echo "There was a problem!\n";
                            print_r($cmd.'\n');
                            print_r($out);
                        } else {
                            echo "Ffmpeg successfully merged audi/video files into single WebM container!\n";

                            unlink($audioFile);
                            unlink($videoFile);
                        }
                    }
                }
            }
        }
    }
    
    public function faceBook($id) {
        $faceInterview = FaceInterviewModel::find($id);
        $param['faceInterview'] = $faceInterview;
        $param['faceInterviews'] = FaceInterviewModel::where('company_id', $faceInterview->company_id)
                                                     ->where('user_id', $faceInterview->user_id)
                                                     ->get();
        $param['company'] = CompanyModel::find($faceInterview->company_id);
        return View::make('interview.face.book')->with($param);
    }    
    
    public function faceDoBook() {
        $id = Input::get('id');
        $interview_date = Input::get('interview_date');
        $start_at = Input::get('start_at');
        
        $faceInterview = FaceInterviewModel::find($id);
        $faceInterview->interview_date = $interview_date;
        $faceInterview->start_at = date('H:i:00', strtotime($start_at) + 0);
        $faceInterview->save();
        
        return Redirect::route('user.job.home');
    }

    public function faceBooking($slug) {
        $company = CompanyModel::findBySlug($slug);
        $param['faceInterviews'] = FaceInterviewModel::where('company_id', $company->id)->get();
        $param['company'] = $company;
        return View::make('interview.face.booking')->with($param);
    }
    
    public function asyncCreateFaceBooking() {
        $faceInterview = FaceInterviewModel::find(Input::get('face_interview_id'));
        $faceInterview->interview_date = Input::get('interview_date');
        $faceInterview->start_at = Input::get('start_at');
        $faceInterview->title = Input::get('title');
        $faceInterview->description = Input::get('description');
        $faceInterview->save();
         
        return Response::json(['result' => 'success', 'msg' => 'You have booked interview successfully', ]);
    }    
    
    public function asyncSaveVideoResponses() {
        $cvcID = Input::get('cvc_id');
        $questionIds = explode(',', Input::get('questions_id'));
        $responseFiles = explode(',', Input::get('response_files'));

        for ($i = 0; $i < count($questionIds); $i ++) {

            $viResponse = new CompanyVIResponseModel;

            $viResponse->cvc_id = $cvcID;
            $viResponse->question_id = $questionIds[$i];
            $viResponse->file_name = $responseFiles[$i];

            $viResponse->save();
        }

        $cvc = CompanyVICreatedModel::find($cvcID);
        $cUser = UserModel::find($cvc->user_id);
        $company = CompanyModel::find($cvc->company_id);

        Queue::push('\SH\Queue\InterviewNotiCompanyMessage', ['user_id' => $cUser->id, 'company_id' => $company->id] );

        return Response::json(['result' => 'success', 'msg' => 'File saved successfully.']);
    }

}
