<?php namespace Batch;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, Log, Mail, URL;
use User as UserModel;
use JobRecommend as JobRecommendModel;
use Email as EmailModel;
use Job as JobModel;

class MarketingMessageController extends \BaseController {
    
    public function recommendedJobReminder($period = 30) {
        if (\SH\Models\Setting::findByCode('CD01')->value == 'NO') {
            return;
        }
        
        $users = UserModel::where('is_active', 1)->get();
        $userEmails = [];
        $userEmails[] = '';
        
        foreach ($users as $user) {
            $userEmails[] = $user->email;
        }
        
        $jobRecommends = JobRecommendModel::whereNotIn('email', $userEmails)
                                          ->whereRaw("DATE(DATE_ADD(NOW(), INTERVAL -".$period." DAY)) = DATE(updated_at)")
                                          ->get();
        $email = EmailModel::findByCode('ET28');
        foreach ($jobRecommends as $jobRecommend) {
            
            $job = JobModel::find($jobRecommend->job_id);
            
            $similar_jobs = "";
            foreach ($job->similar() as $similar) {
                $similar_jobs .= "<p><a href='".URL::route('user.dashboard.viewJob', $similar->slug)."'>".$similar->name."</a></p>";
            }
            
            
            $body = str_replace('{username}', $jobRecommend->name, $email->body);
            $body = str_replace('{job_link}', URL::route('user.dashboard.viewJob', $job->slug), $body);
            $body = str_replace('{job_name}', $job->name, $body);
            $body = str_replace('{similar_jobs}', $similar_jobs, $body);
            
            $data = ['body' => $body];
            	
            $info = [ 'reply_name'  => REPLY_NAME,
                      'reply_email' => REPLY_EMAIL,
                      'email'       => $jobRecommend->email,
                      'name'        => $jobRecommend->name,
                      'subject'     => $email->subject,
                    ];
            
            Mail::send('emails.blank', $data, function($message) use($info) {
                $message->from($info['reply_email'], $info['reply_name']);
                $message->to($info['email'], $info['name'])
                        ->subject($info['subject']);
            });
        }
    }
    
    public function userLoginReminder($period = 30) {
        if (\SH\Models\Setting::findByCode('CD01')->value == 'NO') {
            return;
        }
                
        $users = UserModel::whereRaw("DATE(DATE_ADD(NOW(), INTERVAL -".$period." DAY)) = DATE(updated_at)")
                          ->get();
        
        $email = EmailModel::findByCode('ET29');
        
        foreach ($users as $user) {
            $similar_jobs = "";
            foreach ($user->matchJobs() as $similar) {
                $similar_jobs .= "<p><a href='".URL::route('user.dashboard.viewJob', $similar->slug)."'>".$similar->name."</a></p>";
            }
        
            $body = str_replace('{username}', $user->name, $email->body);
            $body = str_replace('{similar_jobs}', $similar_jobs, $body);
        
            $data = ['body' => $body];
             
            $info = [ 'reply_name'  => REPLY_NAME,
                      'reply_email' => REPLY_EMAIL,
                      'email'       => $user->email,
                      'name'        => $user->name,
                      'subject'     => $email->subject,
                    ];

            Mail::send('emails.blank', $data, function($message) use($info) {
                $message->from($info['reply_email'], $info['reply_name']);
                $message->to($info['email'], $info['name'])
                        ->subject($info['subject']);
            });
        }        
    }
    
    public function companyLoginReminder($period = 30) {
        if (\SH\Models\Setting::findByCode('CD01')->value == 'NO') {
            return;
        }
                
        $companies = CompanyModel::whereRaw("DATE(DATE_ADD(NOW(), INTERVAL -".$period." DAY)) = DATE(updated_at)")
                                 ->where('is_spam', 0)
                                 ->where('is_active', 1)
                                 ->where('is_admin', 1)
                                 ->get();
    
        $email = EmailModel::findByCode('ET30');
    
        foreach ($companies as $company) {
            $body = str_replace('{company_name}', $company->name, $email->body);
    
            $data = ['body' => $body];
             
            $info = [ 'reply_name'  => REPLY_NAME,
                      'reply_email' => REPLY_EMAIL,
                      'email'       => $company->email,
                      'name'        => $company->name,
                      'subject'     => $email->subject,
                    ];
    
            Mail::send('emails.blank', $data, function($message) use($info) {
                $message->from($info['reply_email'], $info['reply_name']);
                $message->to($info['email'], $info['name'])
                        ->subject($info['subject']);
            });
        }
    }
    
    public function companyJobAddedNotRegistered($period = 1) {
        if (\SH\Models\Setting::findByCode('CD01')->value == 'NO') {
            return;
        }
                
        $jobs = JobModel::whereRaw("DATE(DATE_ADD(NOW(), INTERVAL -".$period." DAY)) = DATE(updated_at)")
                        ->where('is_crawled', 1)
                        ->whereNotNull('company_id')
                        ->get();
        $email = EmailModel::findByCode('ET31');
        
        foreach ($jobs as $job) {
            if ($job->company->is_active == 0 && $job->company->is_spam == 0) {
                $body = str_replace('{job_link}', URL::route('user.dashboard.viewJob', ['slug' => $job->slug, 'company_id' => $job->company_id, ]), $email->body);
                $body = str_replace('{job_name}', $job->name, $body);
                $body = str_replace('{company_name}', $job->company->name, $body);
                
                $data = ['body' => $body];
                
                $info = [ 'reply_name'  => REPLY_NAME,
                          'reply_email' => REPLY_EMAIL,
                          'email'       => $job->company->email,
                          'name'        => $job->company->name,
                          'subject'     => $email->subject,
                        ];
                
                Mail::send('emails.blank', $data, function($message) use($info) {
                    $message->from($info['reply_email'], $info['reply_name']);
                    $message->to($info['email'], $info['name'])
                            ->subject($info['subject']);
                });                
            }
        }
        
    }
    
    public function companyJobAddedRegistered($period = 1) {
        if (\SH\Models\Setting::findByCode('CD01')->value == 'NO') {
            return;
        }
                
        $jobs = JobModel::whereRaw("DATE(DATE_ADD(NOW(), INTERVAL -".$period." DAY)) = DATE(updated_at)")
                        ->where('is_crawled', 1)
                        ->whereNotNull('company_id')
                        ->get();
        $email = EmailModel::findByCode('ET32');
    
        foreach ($jobs as $job) {
            if ($job->company->is_active == 1 && $job->company->is_spam == 0) {
                $body = str_replace('{job_link}', URL::route('user.dashboard.viewJob', ['slug' => $job->slug, 'company_id' => $job->company_id, ]), $email->body);
                $body = str_replace('{job_name}', $job->name, $body);
                $body = str_replace('{company_name}', $job->company->name, $body);
    
                $data = ['body' => $body];
    
                $info = [ 'reply_name'  => REPLY_NAME,
                          'reply_email' => REPLY_EMAIL,
                          'email'       => $job->company->email,
                          'name'        => $job->company->name,
                          'subject'     => $email->subject,
                        ];
    
                Mail::send('emails.blank', $data, function($message) use($info) {
                    $message->from($info['reply_email'], $info['reply_name']);
                    $message->to($info['email'], $info['name'])
                            ->subject($info['subject']);
                });
            }
        }
    }
    
    public function setRecommendationBonus() {
        if (\SH\Models\Setting::findByCode('CD01')->value == 'NO') {
            return;
        }
                
        $period = \SH\Models\Setting::findByCode('CD02')->value;
        
        $jobs = JobModel::whereRaw("DATE(DATE_ADD(NOW(), INTERVAL -".$period." DAY)) = DATE(updated_at)")
                        ->where('bonus', 0)
                        ->get();
        
        $email = EmailModel::findByCode('ET33');
        
        foreach ($jobs as $job) {
            $body = str_replace('{job_link}', URL::route('user.dashboard.viewJob', ['slug' => $job->slug, 'company_id' => $job->company_id, ]), $email->body);
            $body = str_replace('{job_name}', $job->name, $body);
            $body = str_replace('{company_name}', $job->company->name, $body);
    
            $data = ['body' => $body];
    
            $info = [ 'reply_name'  => REPLY_NAME,
                      'reply_email' => REPLY_EMAIL,
                      'email'       => $job->company->email,
                      'name'        => $job->company->name,
                      'subject'     => $email->subject,
                    ];
    
            Mail::send('emails.blank', $data, function($message) use($info) {
                $message->from($info['reply_email'], $info['reply_name']);
                $message->to($info['email'], $info['name'])
                        ->subject($info['subject']);
            });
        }
    }
    
    public function createVideoInterview() {
        if (\SH\Models\Setting::findByCode('CD01')->value == 'NO') {
            return;
        }
        
        $period = \SH\Models\Setting::findByCode('CD03')->value;
    
        $jobs = JobModel::whereRaw("DATE(DATE_ADD(NOW(), INTERVAL -".$period." DAY)) = DATE(updated_at)")
                        ->where('bonus', 0)
                        ->get();
    
        $email = EmailModel::findByCode('ET34');
    
        foreach ($jobs as $job) {
            if (count($job->cvcs) > 0) {
                $body = str_replace('{job_link}', URL::route('user.dashboard.viewJob', ['slug' => $job->slug, 'company_id' => $job->company_id, ]), $email->body);
                $body = str_replace('{job_name}', $job->name, $body);
                $body = str_replace('{company_name}', $job->company->name, $body);
        
                $data = ['body' => $body];
        
                $info = [ 'reply_name'  => REPLY_NAME,
                          'reply_email' => REPLY_EMAIL,
                          'email'       => $job->company->email,
                          'name'        => $job->company->name,
                          'subject'     => $email->subject,
                        ];
        
                Mail::send('emails.blank', $data, function($message) use($info) {
                    $message->from($info['reply_email'], $info['reply_name']);
                    $message->to($info['email'], $info['name'])
                            ->subject($info['subject']);
                });
            }
        }
    }
    
    
    
}
