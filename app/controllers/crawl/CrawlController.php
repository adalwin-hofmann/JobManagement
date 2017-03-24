<?php namespace Crawl;

use Illuminate\Routing\Controllers\Controller, URL;
use anlutro\cURL\cURL;
use Symfony\Component\DomCrawler\Crawler, Mail;
use Intervention\Image\ImageManagerStatic as Image;
use Sunra\PhpSimple\HtmlDomParser;
use Category as CategoryModel;
use Job as JobModel;
use Company as CompanyModel;
use Teamsize as TeamsizeModel;
use City as CityModel;
use Level as LevelModel;
use Presence as PresenceModel;
use Language as LanguageModel;
use Type as TypeModel;
use Country as CountryModel;
use CrawledHistory as CrawledHistoryModel;
use Email as EmailModel;


class CrawlController extends \BaseController {
    
    public function getFromMolFi() {

        if ( \SH\Models\Setting::findByCode('CD04')->value == "NO") {
            return;
        }

        $curl = new cURL;

        //get All Category
        $response = $curl->get('http://www.mol.fi/tyopaikat/tyopaikkatiedotus/ws/koodistot/AMMATTIALA?koodi=%3F+%3F%3F+%3F%3F%3F+%3F%3F%3F%3F+-X+-%2FX%5B1-9%5D%2F+-X%3F%3F+-X%3F%3F%3F&sort=rivino+asc&_=1429295480416');

        $jsonData = json_decode($response->body);

        $cArray = $jsonData->{'response'}->{'docs'};

        $categoryCount  = 0;
        $startCode = '';

        for ($i = 0; $i < count($cArray); $i ++) {

            $citem = $cArray[$i];

            $category_name = $citem->{'kuvaus'};
            $category_code = $citem->{'koodi'};

            $ctNames[] = $category_name;
            $ctCodes[] = $category_code;

            $count = CategoryModel::where('name', $category_name)->get()->count();

            if ($count > 0) {
                $category = CategoryModel::where('name', $category_name)->firstOrFail();
                $ctIds[$category_code] = $category->id;
                continue;
            }

            $category = new CategoryModel;
            $category->name = $category_name;

            for ($j = strlen($category_code); $j > 0; $j --) {
                $rcode = substr($category_code, 0, $j);

                if (isset($ctIds[$rcode])) {
                    $category->parent_id = $ctIds[$rcode];
                    break;
                }
            }

            $category->is_crawled = 1;
            $category->save();
            $ctIds[$category_code] = $category->id;

        }

        //get Job Id List

        $response = $curl->get('http://www.mol.fi/tyopaikat/tyopaikkatiedotus/ws/tyopaikat?kentat=ammattikoodi%2Cilmoitusnumero&rows=1000000&_=1429326702715');

        $jsonData = json_decode($response->body);

        $jobCount = $jsonData->{'response'}->{'numFound'};

        $jArray = $jsonData->{'response'}->{'docs'};

        $txt_jobids = '';


        for ($i = 0; $i < count($jArray); $i ++) {
            $jItem = $jArray[$i];

            $jobIds[] = $jItem->{'ilmoitusnumero'};

            if ($txt_jobids == '') {
                $txt_jobids = $jItem->{'ilmoitusnumero'};
            }else {
                $txt_jobids .= ',' . $jItem->{'ilmoitusnumero'};
            }

            $jobCtCode = $jItem->{'ammattikoodi'}[0];

            for ($j = strlen($jobCtCode); $j > 0; $j --) {
                $jobRCode = substr($jobCtCode, 0, $j);
                if (isset($ctIds[$jobRCode])) {
                    $jobCtIds[] = $ctIds[$jobRCode];
                    break;
                }
            }
        }

        //get Old Jobs
        $oldJobsCount = CrawledHistoryModel::where('site_id', 1)->get()->count();

        $oldJobs = '';
        if ($oldJobsCount != 0) {
            $oldJobId = CrawledHistoryModel::where('site_id', 1)->max('id');
            $cHistory = CrawledHistoryModel::find($oldJobId);
            $oldJobs = $cHistory->jobIds;

        }



        //insert New Jobs
        $cHistory = new CrawledHistoryModel;

        $cHistory->site_id = 1;
        $cHistory->jobIds = "$txt_jobids";

        $cHistory->save();


        //update job Status for closed
        $oJobs = explode(',', $oldJobs);

        if ($oldJobs != '') {
            for ($i = 0; $i < count($oJobs); $i ++) {
                $oJob_id = $oJobs[$i];
                if (strpos($txt_jobids, $oJob_id) === false) {
                    $count = JobModel::where('id_crawled', '1-' . $oJob_id)->get()->count();
                    if ($count > 0) {
                        $job = JobModel::where('id_crawled', '1-' . $oJob_id)->firstOrFail();
                        $job->status = 2;
                        $job->save();
                    }
                }
            }
        }



        //get Job Details

        for ($i = 0; $i < count($jobIds); $i ++) {

            $crawledId = '1-'.$jobIds[$i];

            if ($crawledId == '1-8496169') {
                $hh = 1;
            }

            $count = JobModel::where('id_crawled', $crawledId)->get()->count();

            if ($count > 0) continue;

            $link = 'http://www.mol.fi/tyopaikat/tyopaikkatiedotus/ws/tyopaikat/' . $jobIds[$i];

            $response = $curl->get($link);

            $jsonData = json_decode($response->body);

            $detailDatas = $jsonData->{'response'}->{'docs'}[0];

            $company_id = 0;
            $job_name = $detailDatas->{'otsikko'};

            $job_title = trim(explode(',', $job_name)[0]);

            $city_name = trim(explode(',', $job_name)[count(explode(',', $job_name)) - 1]);

            if ( count(explode(',', $job_name)) == 3 ) {
                if (strpos(explode(',', $job_name)[1], 'paikkaa') !== false) {
                    $city_name = '';
                }
            }elseif ( count(explode(',', $job_name)) <3 ) {
                continue;
            }

            $job_description = $detailDatas->{'kuvausteksti'};


            if (isset($city_name) && $city_name != '') {

                $count = CityModel::where('name', $city_name)->get()->count();

                if ($count  == 0) {
                    $city = new CityModel;

                    $city->name = $city_name;
                    $city->country_id = CountryModel::whereRaw(true)->min('id');

                    $city->save();

                    $city_id = $city->id;
                }else {
                    $city = CityModel::where('name', $city_name)->firstOrFail();

                    $city_id = $city->id;
                }

            }
            else {
                $city = new CityModel;
                $city->country_id = CountryModel::whereRaw(true)->min('id');
                $city->save();

                $city_id = $city->id;
            }



            $jobEmail = '';
            $link_address = '';
            //get Contact address or Apply Link
            if (isset($detailDatas->{'yhteystiedot'})) {
                foreach(preg_split('/\s/', $detailDatas->{'yhteystiedot'}) as $token) {
                    $email = filter_var(filter_var($token, FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL);
                    if ($email !== false) {
                        $jobEmail = $email;
                        break;
                    }
                }

                if ($jobEmail == '') {
                    $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";

                    if(preg_match($reg_exUrl, $detailDatas->{'yhteystiedot'}, $url) && $company_id == 0) {
                        $link_address = $url[0];
                    }
                }
            }


            //set company
            if (count(explode(',', $job_name)) >= 4) {
                $company_name = trim(explode(',', $job_name)[2]);
            }
            else {
                $company_name = trim(explode(',', $job_name)[1]);

                if (strpos($company_name, 'paikkaa') !== false) {
                    $company_name = trim(explode(',', $job_name)[2]);
                }
            }

            $company_name = trim($company_name);


            if ($jobEmail != '') {

                $company_email = $jobEmail;
                $count = CompanyModel::where('email', $company_email)->get()->count();

                if ($count == 0) {
                    $company = new CompanyModel;
                    $company->salt = str_random(8);
                    $company->secure_key = md5($company->salt . $company->salt);
                    $company->teamsize_id = TeamsizeModel::whereRaw(true)->min('id');
                    $company->category_id = CategoryModel::whereRaw(true)->min('id');
                    $company->city_id = CityModel::whereRaw(true)->min('id');
                    $company->name = $company_name;
                    $company->email = $company_email;
                    $company->logo = 'default_company_logo.gif';
                    $company->is_admin = 1;
                    $company->is_finished = 0;
                    $company->is_crawled = 1;
                    $company->overlay_color = 'rgba(0, 82, 208, 0.9)';

                    $company->save();

                    $company->parent_id = $company->id;
                    $company->save();
                }

                $company = CompanyModel::where('email', $company_email)->firstOrFail();
                $company_id = $company->id;

                $link_address = '';

            }
            elseif (isset($detailDatas->{'hakemusLahetetaan'})) {

                $company_email = '';

                $data = explode(' ', $detailDatas->{'hakemusLahetetaan'});

                for ($j = 0; $j < count($data); $j++) {
                    if (strpos($data[$j], '@') !== false) {
                        if (filter_var($data[$j], FILTER_VALIDATE_EMAIL)) {
                            $company_email = $data[$j];
                            break;
                        }
                    }
                }

                if ($company_email != '') {
                    $count = CompanyModel::where('email', $company_email)->get()->count();

                    if ($count == 0) {
                        $company = new CompanyModel;
                        $company->salt = str_random(8);
                        $company->secure_key = md5($company->salt . $company->salt);
                        $company->teamsize_id = TeamsizeModel::whereRaw(true)->min('id');
                        $company->category_id = CategoryModel::whereRaw(true)->min('id');
                        $company->city_id = CityModel::whereRaw(true)->min('id');
                        $company->name = $company_name;
                        $company->email = $company_email;
                        $company->logo = 'default_company_logo.gif';
                        $company->is_admin = 1;
                        $company->is_finished = 0;
                        $company->is_crawled = 1;

                        $company->save();

                        $company->parent_id = $company->id;
                        $company->save();
                    }

                    $company = CompanyModel::where('email', $company_email)->firstOrFail();
                    $company_id = $company->id;

                    $link_address = '';
                }else {

                    $count = CompanyModel::where('name', $company_name)->get()->count();

                    if ($count == 0) {
                        $company_email = str_random(10).'@gmail.com';

                        $company = new CompanyModel;
                        $company->salt = str_random(8);
                        $company->secure_key = md5($company->salt . $company->salt);
                        $company->teamsize_id = TeamsizeModel::whereRaw(true)->min('id');
                        $company->category_id = CategoryModel::whereRaw(true)->min('id');
                        $company->city_id = CityModel::whereRaw(true)->min('id');
                        $company->name = $company_name;
                        $company->email = $company_email;
                        $company->logo = 'default_company_logo.gif';
                        $company->is_admin = 1;
                        $company->is_finished = 0;
                        $company->is_crawled = 1;
                        $company->is_spam = 1;

                        $company->save();

                        $company->parent_id = $company->id;
                        $company->save();
                    }else {
                        $company = CompanyModel::where('name', $company_name)->firstOrFail();
                    }

                    $company_id = $company->id;

                    if ($link_address == '') {
                        if (isset($detailDatas->{'wwwTyonhakulomake'})) {
                            $link_address = $detailDatas->{'wwwTyonhakulomake'};
                        }elseif ((isset($detailDatas->{'tyonantajanWwwOsoite'})))  {
                            $link_address = $detailDatas->{'tyonantajanWwwOsoite'};
                        }
                    }
                }
            }
            elseif (isset($detailDatas->{'yhteystiedotSahkoposti'})) {

                $company_email = $detailDatas->{'yhteystiedotSahkoposti'}[0];
                $count = CompanyModel::where('email', $company_email)->get()->count();

                if ($count == 0) {
                    $company = new CompanyModel;
                    $company->salt = str_random(8);
                    $company->secure_key = md5($company->salt . $company->salt);
                    $company->teamsize_id = TeamsizeModel::whereRaw(true)->min('id');
                    $company->category_id = CategoryModel::whereRaw(true)->min('id');
                    $company->city_id = CityModel::whereRaw(true)->min('id');
                    $company->name = $company_name;
                    $company->email = $company_email;
                    $company->logo = 'default_company_logo.gif';
                    $company->is_admin = 1;
                    $company->is_finished = 0;
                    $company->is_crawled = 1;

                    $company->save();

                    $company->parent_id = $company->id;
                    $company->save();
                }

                $company = CompanyModel::where('email', $company_email)->firstOrFail();
                $company_id = $company->id;
                $link_address = '';
            }
            else {

                $count = CompanyModel::where('name', $company_name)->get()->count();

                if ($count == 0) {
                    $company_email = str_random(10).'@gmail.com';

                    $company = new CompanyModel;
                    $company->salt = str_random(8);
                    $company->secure_key = md5($company->salt . $company->salt);
                    $company->teamsize_id = TeamsizeModel::whereRaw(true)->min('id');
                    $company->category_id = CategoryModel::whereRaw(true)->min('id');
                    $company->city_id = CityModel::whereRaw(true)->min('id');
                    $company->name = $company_name;
                    $company->email = $company_email;
                    $company->logo = 'default_company_logo.gif';
                    $company->is_admin = 1;
                    $company->is_finished = 0;
                    $company->is_crawled = 1;
                    $company->is_spam = 1;

                    $company->save();

                    $company->parent_id = $company->id;
                    $company->save();
                }else {
                    $company = CompanyModel::where('name', $company_name)->firstOrFail();
                }

                $company_id = $company->id;

                if ($link_address == '') {
                    if (isset($detailDatas->{'wwwTyonhakulomake'})) {
                        $link_address = $detailDatas->{'wwwTyonhakulomake'};
                    }elseif ((isset($detailDatas->{'tyonantajanWwwOsoite'})))  {
                        $link_address = $detailDatas->{'tyonantajanWwwOsoite'};
                    }
                }
            }

            $count = JobModel::where('is_crawled', 1)->where('id_crawled', $crawledId)->get()->count();

            if ($count == 0) {
                $job = new JobModel;
            }else {
                $job = JobModel::where('is_crawled', 1)->where('id_crawled', $crawledId)->firstOrFail();
            }

            if ($link_address != '') {
                $job->link_address = $link_address;
                $job->by_company = 0;
            }else {
                $job->by_company = 1;
            }

            if ($jobEmail == '' && $link_address == '') {
                $job->link_address = "http://www.mol.fi/tyopaikat/tyopaikkatiedotus/haku/".$jobIds[$i]."_fi.htm";
            }


            $job->company_id = $company_id;
            $job->job_link = "http://www.mol.fi/tyopaikat/tyopaikkatiedotus/haku/".$jobIds[$i]."_fi.htm";
            $job->name = $job_title;
            $job->level_id = LevelModel::whereRaw(true)->min('id');
            $job->description = $job_description;
            $job->category_id = $jobCtIds[$i];
            $job->presence_id = PresenceModel::whereRaw(true)->min('id');
            $job->year = 5;
            $job->city_id = $city_id;
            $job->native_language_id = LanguageModel::whereRaw(true)->min('id');
            $job->requirements = '';
            $job->is_name = 0;
            $job->is_phonenumber = 0;
            $job->is_email = 0;
            $job->is_currentjob = 0;
            $job->is_previousjobs = 0;
            $job->is_description = 0;
            $job->is_verified = 0;
            $job->bonus = 0;
            $job->type_id = TypeModel::whereRaw(true)->min('id');
            $job->salary = 0;
            $job->email = $jobEmail;
            $job->phone = '';
            $job->lat = 0;
            $job->long = 0;
            $job->is_finished = 1;
            $job->salary = 0;
            $job->paid_after = '';
            $job->bonus_description = '';
            $job->is_active = 0;
            $job->is_crawled = 1;
            $job->id_crawled = $crawledId;

            $job->save();
            
            $this->sendEmailToCompany($job->id);
        }
    }

    public function getFromMonsterFi() {

        if ( \SH\Models\Setting::findByCode('CD05')->value == "NO") {
            return;
        }

        //get category
        $html = HtmlDomParser::file_get_html('http://hae.monster.fi/selaa/?sf=14&re=nv_gh_gnl1147_%2F');

        $html_categories = $html->find('ul#facetsList')[0];

        $catLinks = [];
        $catIds = [];
        $catCount = 0;

        foreach ($html_categories->find('li') as $element) {
            $aTag = $element->find('a')[0];
            $class = $aTag->class;
            $catName = $aTag->title;

            if ($class == 'disabled') continue;

            $count = CategoryModel::where('name', $catName)->get()->count();

            if ($count > 0) {
                $cat = CategoryModel::where('name', $catName)->firstOrFail();
            }else {
                $cat = new CategoryModel;

                $cat->name = $catName;
                $cat->is_crawled = 1;

                $cat->save();
            }

            $catLinks[$catCount] = $aTag->href;
            $catIds[$catCount] = $cat->id;
            $catCount ++;
        }

        //check job status

        //get Old Jobs
        $oldJobsCount = CrawledHistoryModel::where('site_id', 2)->get()->count();

        $oldJobs = '';
        if ($oldJobsCount != 0) {
            $oldJobId = CrawledHistoryModel::where('site_id', 2)->max('id');
            $cHistory = CrawledHistoryModel::find($oldJobId);
            $oldJobs = $cHistory->jobIds;
        }



        $jobText = '';
        $responses = [];

        for ($i = 0; $i < $catCount; $i ++) {
            $curl = new cURL;

            //get All Category
            $response = $curl->get($catLinks[$i]);
            $responses[$i] = $response;

            $array_jobs = explode('slJobTitle', $response);

            $index = -1;
            foreach ($array_jobs as $aJob) {
                $index++;
                if ($index == 0 || $index % 2 == 1) continue;

                $str_name = explode('name="', $aJob)[1];
                $jCode = substr($str_name, 0, strpos($str_name, '"'));

                $crawledId = '2-'.$jCode;

                if (JobModel::where('id_crawled', $crawledId)->get()->count() > 0) {
                    if (strpos($oldJobs, $jCode) === false) {
                        $job = JobModel::where('id_crawled', $crawledId)->firstOrFail();

                        $job->status = 2;
                        $job->save();
                    }
                }

                if (strlen($jobText) != 0) $jobText .= ',';
                $jobText .= $jCode;
            }
        }

        $crawlHistory = new CrawledHistoryModel;

        $crawlHistory->site_id = 2;
        $crawlHistory->jobIds = $jobText;

        $crawlHistory->save();



        //get jobs for each category

        for ($i = 0; $i < $catCount; $i ++) {

            //get All Category
            $response = $responses[$i];

            $array_jobs = explode('slJobTitle', $response);

            $index = -1;
            foreach ($array_jobs as $aJob) {
                $index ++;
                if ($index == 0 || $index % 2 == 1) continue;

                $str_title = explode('href="', $aJob)[1];
                $jLink = substr($str_title, 0, strpos($str_title, '"'));


                $str_name = explode('name="', $aJob)[1];
                $jCode = substr($str_name, 0, strpos($str_name, '"'));

                $realIndex = ($index - 2) / 2;

                $jobLinks[$realIndex] = $jLink;
                $jobCodes[$realIndex] = $jCode;
            }

            $job_html = HtmlDomParser::str_get_html($response->body);

            $job_tables = $job_html->find('table.listingsTable')[0];

            $index = -2;

//            echo $job_tables.'<br/>';

            foreach ($job_tables->find('tr') as $element) {
                $index ++;
                if ($index == -1) continue;

                if (count($element->find('td')[1]->find('div')) == 0) continue;
                if (count($element->find('td')[1]->find('div')[0]->find('div')) == 0) continue;
                if (count($element->find('td')[1]->find('div')[0]->find('div')[0]->find('a')) == 0) continue;

                $jobTitle = $element->find('td')[1]->find('div')[0]->find('div')[0]->find('a')[0]->text();
                $jobName = $jobTitle;
                $jobLink = $jobLinks[$index];
                $jobCode = $jobCodes[$index];

                $crawledId = '2-'.$jobCode;

                if (JobModel::where('id_crawled', $crawledId)->get()->count() > 0) continue;

                $company_name = $element->find('td')[1]->find('div')[0]->find('div')[1]->find('div')[0]->find('a')[1]->text();

                if (count($element->find('td')[2]->find('div')[0]->find('div')[0]->find('a')) > 0) {
                    $cityName = $element->find('td')[2]->find('div')[0]->find('div')[0]->find('a')[0]->title;
                }else {
                    continue;
                }


                $jCurl = new cURL;

                //get All Category
                $response = $jCurl->get($jobLink);

                $job_detail_html = HtmlDomParser::str_get_html($response->body);
                $jobEmail = '';

                if (count($job_detail_html->find('div#jobBodyContent')) > 0) {
                    $jobDes = $job_detail_html->find('div#jobBodyContent')[0];

                }elseif (count($job_detail_html->find('div#bodycol')) > 0) {
                    if ($job_detail_html->find('div#bodycol')[0]->itemprop != '') {
                        $jobDes = $job_detail_html->find('div#bodycol')[0];
                    }else {
                        if (count($job_detail_html->find('div#CJT_jobBodyContent')) > 0) {
                            $jobDes = $job_detail_html->find('div#CJT_jobBodyContent')[0];

                            foreach ($job_detail_html->find('div#CJT_jobBodyContent')[0]->find('a') as $element) {
                                $text = $element->href;
                                if (strpos($text, 'mailto') !== false) {
                                    $jobEmail = $element->text();
                                    break;
                                }
                            }

                        }else {
                            continue;
                        }
                    }

                }elseif (count($job_detail_html->find('div#content_text')) > 0) {
                    $jobDes = $job_detail_html->find('div#content_text')[0];

                    if (count($job_detail_html->find('div#content_text')[0]->find('p')) >= 4) {
                        $contactText =  $job_detail_html->find('div#content_text')[0]->find('p')[3]->text();

                        foreach(preg_split('/\s/', $contactText) as $token) {
                            $email = filter_var(filter_var($token, FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL);
                            if ($email !== false) {
                                $jobEmail = $email;
                                break;
                            }
                        }
                    }

                }elseif (count($job_detail_html->find('div#jobdesc')) > 0) {
                    $jobDes = $job_detail_html->find('div#jobdesc')[0];
                }else {
                    continue;
                }

                $count = CityModel::where('name', $cityName)->get()->count();

                if ($count  == 0) {
                    $city = new CityModel;

                    $city->name = $cityName;
                    $city->country_id = CountryModel::whereRaw(true)->min('id');

                    $city->save();

                    $city_id = $city->id;
                }else {
                    $city = CityModel::where('name', $cityName)->firstOrFail();

                    $city_id = $city->id;
                }


                $count = JobModel::where('is_crawled', 1)->where('id_crawled', $crawledId)->get()->count();

                if ($count == 0) {
                    $job = new JobModel;
                }else {
                    $job = JobModel::where('is_crawled', 1)->where('id_crawled', $crawledId)->firstOrFail();
                }

                //set Company

                $query = CompanyModel::where('name', $company_name);
                if ($jobEmail != '') {
                    $query = $query->where('email', $jobEmail);
                }
                $count = $query->get()->count();

                if ($count ==  0) {
                    $company_email = str_random(10).'@gmail.com';

                    if ($jobEmail != '') $company_email = $jobEmail;

                    $company = new CompanyModel;
                    $company->salt = str_random(8);
                    $company->secure_key = md5($company->salt . $company->salt);
                    $company->teamsize_id = TeamsizeModel::whereRaw(true)->min('id');
                    $company->category_id = CategoryModel::whereRaw(true)->min('id');
                    $company->city_id = CityModel::whereRaw(true)->min('id');
                    $company->name = $company_name;
                    $company->email = $company_email;
                    $company->logo = 'default_company_logo.gif';
                    $company->is_admin = 1;
                    $company->is_finished = 0;
                    $company->is_crawled = 1;
                    $company->is_spam = 1;

                    $company->save();

                    $company->parent_id = $company->id;
                    $company->save();

                    $company_id = $company->id;
                }else {
                    $company = CompanyModel::where('name', $company_name)->firstOrFail();

                    $company_id = $company->id;
                }

                $job->job_link = $jobLink;

                if ($jobEmail != '') {
                    $jobLink = '';
                }

                $job->link_address = $jobLink;

                $job->company_id = $company_id;
                $job->name = $jobName;
                $job->level_id = LevelModel::whereRaw(true)->min('id');
                $job->description = $jobDes;
                $job->category_id = $catIds[$i];
                $job->presence_id = PresenceModel::whereRaw(true)->min('id');
                $job->year = 5;
                $job->city_id = $city_id;
                $job->native_language_id = LanguageModel::whereRaw(true)->min('id');
                $job->requirements = '';
                $job->is_name = 0;
                $job->is_phonenumber = 0;
                $job->is_email = 0;
                $job->is_currentjob = 0;
                $job->is_previousjobs = 0;
                $job->is_description = 0;
                $job->is_verified = 0;
                $job->bonus = 0;
                $job->type_id = TypeModel::whereRaw(true)->min('id');
                $job->salary = 0;
                $job->email = $jobEmail;
                $job->phone = '';
                $job->lat = 0;
                $job->long = 0;
                $job->is_finished = 1;
                $job->salary = 0;
                $job->paid_after = '';
                $job->bonus_description = '';
                $job->is_active = 0;
                $job->is_crawled = 1;
                $job->id_crawled = $crawledId;
                $job->by_company = 0;

                $job->save();
                
                $this->sendEmailToCompany($job->id);
            }
        }
    }

    public function getFromCVOnline () {

        if ( \SH\Models\Setting::findByCode('CD06')->value == "NO") {
            return;
        }

        $category_html = HtmlDomParser::file_get_html('http://www.cv.ee/job-ads/all?sort=inserted&dir=desc');

        //get all categories
        $category_div = $category_html->find('div#filters_Tegvk')[0];

        $catCount = 0;
        foreach ($category_div->find('span') as $element) {
            $name = $element->find('a')[0]->text();
            $catName = substr($name, 0, strrpos($name, '(', -1) - 1);

            $catLink = 'http://www.cv.ee'.$element->find('a')[0]->href;

            $count = CategoryModel::where('name', $catName)->get()->count();

            if ($count > 0) {
                $cat = CategoryModel::where('name', $catName)->firstOrFail();
            }else {
                $cat = new CategoryModel;

                $cat->name = $catName;
                $cat->is_crawled = 1;

                $cat->save();
            }

            $catLinks[$catCount] = $catLink;
            $catIds[$catCount] = $cat->id;
            $catCount ++;
        }



        //get Old Jobs
        $oldJobsCount = CrawledHistoryModel::where('site_id', 3)->get()->count();

        $oldJobs = '';
        if ($oldJobsCount != 0) {
            $oldJobId = CrawledHistoryModel::where('site_id', 3)->max('id');
            $cHistory = CrawledHistoryModel::find($oldJobId);
            $oldJobs = $cHistory->jobIds;

        }

        $jobCount = 0;
        $jobText = '';
        $jobNames = [];
        $jobLinks = [];
        $companyLinks = [];
        $companyNames = [];
        $jobCodes = [];
        $cityNames = [];
        $jobCatIds = [];


        //get jobs by category
        for ($i = 0; $i < $catCount; $i ++) {

            $job_html = HtmlDomParser::file_get_html($catLinks[$i]);

            $jobTables = $job_html->find('table#table_jobs')[0];

            $index = 0;

            $count = 0;
            foreach ($jobTables->find('tr') as $element) {
                $index++;
                if ($index == 1) continue;
                if (count($element->find('td')) < 4) continue;


                $code = $element->id;
                $jobCode  = substr($code, strrpos($code, '_', -1) + 1, strlen($code) - strrpos($code, '_', -1));
                $crawledId = '3-' . $jobCode;

                if (strlen($jobText) != 0) $jobText .= ',';
                $jobText .= $jobCode;

                if (JobModel::where('id_crawled', $crawledId)->get()->count() > 0) {
                    if (strpos($oldJobs, $jobCode) === false) {
                        $job = JobModel::where('id_crawled', $crawledId)->firstOrFail();
                        $job->status = 2;
                        $job->save();
                    }

                    continue;
                }

                $count++;
                if ($count > 10) break;

                //get basic info
                $jobLinks[$jobCount] = 'http:' . $element->find('td')[0]->find('a')[0]->href;
                $jobNames[$jobCount] = $element->find('td')[0]->find('a')[0]->text();
                $companyLinks[$jobCount] = $element->find('td')[0]->find('span')[0]->find('a')[0]->href;
                $companyNames[$jobCount] = $element->find('td')[0]->find('span')[0]->find('a')[0]->text();
                $cityNames[$jobCount] = $element->find('td')[1]->find('a')[0]->text();
                $jobCodes[$jobCount] = $jobCode;
                $jobCatIds[$jobCount] = $catIds[$i];

                $jobCount ++;
            }
        }

        $crawlHistory = new CrawledHistoryModel;
        $crawlHistory->site_id = 3;
        $crawlHistory->jobIds = $jobText;
        $crawlHistory->save();

        for ($i = 0; $i < $jobCount; $i ++) {

            $jobLink = $jobLinks[$i];
            $jobName = $jobNames[$i];
            $companyLink = $companyLinks[$i];
            $companyName = $companyNames[$i];
            $cityName = $cityNames[$i];
            $crawledId = '3-'.$jobCodes[$i];



            $jDetail = HtmlDomParser::file_get_html($jobLink);
            $jobTable = $jDetail->find('center')[0]->find('table')[1]->find('tr')[0]->find('td')[0]->find('div')[0]->find('div')[0]->find('div')[0]->find('div')[0]->find('div')[0]->find('table')[0];


            $jobEmail = '';
            $jobDes = '<table><tbody>';
            $flag = 0;

            for ($j = 0; $j < count($jobTable->find('tr')) - 5; $j ++) {
                if ($j == 0) {
                    if (count($jobTable->find('tr')[$j]->find('td')[0]->find('img')) > 0) {
                        $imgUrl = $jobTable->find('tr')[$j]->find('td')[0]->find('img')[0]->src;
                        if (strpos($imgUrl, '//') === false) {
                            $jobTable->find('tr')[$j]->find('td')[0]->find('img')[0]->src = 'http://www.cv.ee'.$jobTable->find('tr')[$j]->find('td')[0]->find('img')[0]->src;
                            $flag = 1;
                        }
                    }
                }

                if (count($jobTable->find('tr')[$j]->find('td')[0]->find('p')) > 0) {
                    foreach ($jobTable->find('tr')[$j]->find('td')[0]->find('p') as $element) {
                        foreach ($element->find('a') as $aTag) {
                            if (strpos($aTag->href, 'mailto') !== false) {
                                $jobEmail = $aTag->text();
                                break;
                            }
                        }
                    }
                }

                if ($flag == 1 && $j == 1) continue;

                $jobDes .= $jobTable->find('tr')[$j];
            }

            $jobDes .='</tbody></table>';

            $count = CityModel::where('name', $cityName)->get()->count();

            if ($count  == 0) {
                $city = new CityModel;

                $city->name = $cityName;
                $city->country_id = CountryModel::whereRaw(true)->min('id');

                $city->save();

                $city_id = $city->id;
            }else {
                $city = CityModel::where('name', $cityName)->firstOrFail();

                $city_id = $city->id;
            }


            $count = JobModel::where('is_crawled', 1)->where('id_crawled', $crawledId)->get()->count();

            if ($count == 0) {
                $job = new JobModel;
            }else {
                $job = JobModel::where('is_crawled', 1)->where('id_crawled', $crawledId)->firstOrFail();
            }

            //set Company

            $query = CompanyModel::where('name', $companyName);
            if ($jobEmail != '') {
                $query = $query->where('email', $jobEmail);
            }
            $count = $query->get()->count();

            if ($count ==  0) {
                $company_email = str_random(10).'@gmail.com';

                if ($jobEmail != '') $company_email = $jobEmail;

                $company = new CompanyModel;
                $company->salt = str_random(8);
                $company->secure_key = md5($company->salt . $company->salt);
                $company->teamsize_id = TeamsizeModel::whereRaw(true)->min('id');
                $company->category_id = CategoryModel::whereRaw(true)->min('id');
                $company->city_id = CityModel::whereRaw(true)->min('id');
                $company->name = $companyName;
                $company->email = $company_email;
                $company->logo = 'default_company_logo.gif';
                $company->is_admin = 1;
                $company->is_finished = 0;
                $company->is_crawled = 1;
                $company->is_spam = 1;

                $company->save();

                $company->parent_id = $company->id;
                $company->save();

                $company_id = $company->id;
            }else {
                $company = CompanyModel::where('name', $companyName)->firstOrFail();

                $company_id = $company->id;
            }

            $job->job_link = $jobLink;

            if ($jobEmail != '') {
                $jobLink = '';
            }

            $job->link_address = $jobLink;

            $job->company_id = $company_id;
            $job->name = $jobName;
            $job->level_id = LevelModel::whereRaw(true)->min('id');
            $job->description = $jobDes;
            $job->category_id = $jobCatIds[$i];
            $job->presence_id = PresenceModel::whereRaw(true)->min('id');
            $job->year = 5;
            $job->city_id = $city_id;
            $job->native_language_id = LanguageModel::whereRaw(true)->min('id');
            $job->requirements = '';
            $job->is_name = 0;
            $job->is_phonenumber = 0;
            $job->is_email = 0;
            $job->is_currentjob = 0;
            $job->is_previousjobs = 0;
            $job->is_description = 0;
            $job->is_verified = 0;
            $job->bonus = 0;
            $job->type_id = TypeModel::whereRaw(true)->min('id');
            $job->salary = 0;
            $job->email = $jobEmail;
            $job->phone = '';
            $job->lat = 0;
            $job->long = 0;
            $job->is_finished = 1;
            $job->salary = 0;
            $job->paid_after = '';
            $job->bonus_description = '';
            $job->is_active = 0;
            $job->is_crawled = 1;
            $job->id_crawled = $crawledId;
            $job->by_company = 0;

            $job->save();
            
            $this->sendEmailToCompany($job->id);            

        }


    }

    public function getFromCVLv () {

        if ( \SH\Models\Setting::findByCode('CD07')->value == "NO") {
            return;
        }

        $category_html = HtmlDomParser::file_get_html('http://www.cv.lv/job-ads/all');

        //get all categories
        $category_div = $category_html->find('div#filters_Tegvk')[0];

        $catCount = 0;
        foreach ($category_div->find('span') as $element) {
            $name = $element->find('a')[0]->text();
            $catName = substr($name, 0, strrpos($name, '(', -1) - 1);

            $catLink = 'http://www.cv.lv'.$element->find('a')[0]->href;

            $count = CategoryModel::where('name', $catName)->get()->count();

            if ($count > 0) {
                $cat = CategoryModel::where('name', $catName)->firstOrFail();
            }else {
                $cat = new CategoryModel;

                $cat->name = $catName;
                $cat->is_crawled = 1;

                $cat->save();
            }

            $catLinks[$catCount] = $catLink;
            $catIds[$catCount] = $cat->id;
            $catCount ++;
        }


        //get Old Jobs
        $oldJobsCount = CrawledHistoryModel::where('site_id', 4)->get()->count();

        $oldJobs = '';
        if ($oldJobsCount != 0) {
            $oldJobId = CrawledHistoryModel::where('site_id', 4)->max('id');
            $cHistory = CrawledHistoryModel::find($oldJobId);
            $oldJobs = $cHistory->jobIds;
        }


        $jobCount = 0;
        $jobText = '';
        $jobNames = [];
        $jobLinks = [];
        $companyLinks = [];
        $companyNames = [];
        $jobCodes = [];
        $cityNames = [];
        $jobCatIds = [];


        //get jobs by category
        for ($i = 0; $i < $catCount; $i ++) {

            $job_html = HtmlDomParser::file_get_html($catLinks[$i]);

            $jobTables = $job_html->find('table#table_jobs')[0];

            $index = 0;

            $count = 0;
            foreach ($jobTables->find('tr') as $element) {
                $index++;
                if ($index == 1) continue;
                if (count($element->find('td')) < 4) continue;


                $code = $element->id;
                $jobCode  = substr($code, strrpos($code, '_', -1) + 1, strlen($code) - strrpos($code, '_', -1));
                $crawledId = '4-' . $jobCode;

                if (strlen($jobText) != 0) $jobText .= ',';
                $jobText .= $jobCode;

                if (JobModel::where('id_crawled', $crawledId)->get()->count() > 0) {
                    if (strpos($oldJobs, $jobCode) === false) {
                        $job = JobModel::where('id_crawled', $crawledId)->firstOrFail();
                        $job->status = 2;
                        $job->save();
                    }

                    continue;
                }

                $count++;
                if ($count > 10) break;

                //get basic info
                $jobLinks[$jobCount] = 'http:' . $element->find('td')[0]->find('a')[0]->href;
                $jobNames[$jobCount] = $element->find('td')[0]->find('a')[0]->text();
                $companyLinks[$jobCount] = $element->find('td')[0]->find('span')[0]->find('a')[0]->href;
                $companyNames[$jobCount] = $element->find('td')[0]->find('span')[0]->find('a')[0]->text();
                $cityNames[$jobCount] = $element->find('td')[1]->find('a')[0]->text();
                $jobCodes[$jobCount] = $jobCode;
                $jobCatIds[$jobCount] = $catIds[$i];

                $jobCount ++;
            }
        }

        $crawlHistory = new CrawledHistoryModel;
        $crawlHistory->site_id = 4;
        $crawlHistory->jobIds = $jobText;
        $crawlHistory->save();


        for ($i = 0; $i < $jobCount; $i ++) {

            $jobLink = $jobLinks[$i];
            $jobName = $jobNames[$i];
            $companyLink = $companyLinks[$i];
            $companyName = $companyNames[$i];
            $cityName = $cityNames[$i];
            $crawledId = '4-' . $jobCodes[$i];


            $jDetail = HtmlDomParser::file_get_html($jobLink);
            $jobTable = $jDetail->find('center')[0]->find('table')[1]->find('tr')[0]->find('td')[0]->find('div')[0]->find('div')[0]->find('div')[0]->find('div')[0]->find('div')[0]->find('table')[0];


            $jobDes = '<table><tbody>';
            $flag = 0;
            $jobEmail = '';

            for ($j = 0; $j < count($jobTable->find('tr')) - 5; $j++) {
                if ($j == 0) {
                    if (count($jobTable->find('tr')[$j]->find('td')[0]->find('img')) > 0) {
                        $imgUrl = $jobTable->find('tr')[$j]->find('td')[0]->find('img')[0]->src;
                        if (strpos($imgUrl, '//') === false) {
                            $jobTable->find('tr')[$j]->find('td')[0]->find('img')[0]->src = 'http://www.cv.ee' . $jobTable->find('tr')[$j]->find('td')[0]->find('img')[0]->src;
                            $flag = 1;
                        }
                    }
                }

                if ($flag == 1 && $j == 1) continue;

                foreach ($jobTable->find('tr')[$j]->find('a') as $aTag) {
                    if (strpos($aTag->href, 'mailto') !== false) {
                        $jobEmail = $aTag->text();
                        break;
                    }
                }

                $jobDes .= $jobTable->find('tr')[$j];
            }

            $jobDes .= '</tbody></table>';

            $count = CityModel::where('name', $cityName)->get()->count();

            if ($count == 0) {
                $city = new CityModel;

                $city->name = $cityName;
                $city->country_id = CountryModel::whereRaw(true)->min('id');

                $city->save();

                $city_id = $city->id;
            } else {
                $city = CityModel::where('name', $cityName)->firstOrFail();

                $city_id = $city->id;
            }


            $count = JobModel::where('is_crawled', 1)->where('id_crawled', $crawledId)->get()->count();

            if ($count == 0) {
                $job = new JobModel;
            } else {
                $job = JobModel::where('is_crawled', 1)->where('id_crawled', $crawledId)->firstOrFail();
            }

            $query = CompanyModel::where('name', $companyName);
            if ($jobEmail != '') {
                $query = $query->where('email', $jobEmail);
            }
            $count = $query->get()->count();

            if ($count == 0) {

                $company_email = str_random(10) . '@gmail.com';

                if ($jobEmail != '') $company_email = $jobEmail;

                $company = new CompanyModel;
                $company->salt = str_random(8);
                $company->secure_key = md5($company->salt . $company->salt);
                $company->teamsize_id = TeamsizeModel::whereRaw(true)->min('id');
                $company->category_id = CategoryModel::whereRaw(true)->min('id');
                $company->city_id = CityModel::whereRaw(true)->min('id');
                $company->name = $companyName;
                $company->email = $company_email;
                $company->logo = 'default_company_logo.gif';
                $company->is_admin = 1;
                $company->is_finished = 0;
                $company->is_crawled = 1;

                $company->save();

                $company->parent_id = $company->id;
                $company->save();

                $company_id = $company->id;
            } else {
                $company = CompanyModel::where('name', $companyName)->firstOrFail();

                $company_id = $company->id;
            }

            $job->job_link = $jobLink;

            if ($jobEmail != '') {
                $jobLink = '';
            }

            $job->link_address = $jobLink;

            $job->company_id = $company_id;
            $job->name = $jobName;
            $job->level_id = LevelModel::whereRaw(true)->min('id');
            $job->description = $jobDes;
            $job->category_id = $jobCatIds[$i];
            $job->presence_id = PresenceModel::whereRaw(true)->min('id');
            $job->year = 5;
            $job->city_id = $city_id;
            $job->native_language_id = LanguageModel::whereRaw(true)->min('id');
            $job->requirements = '';
            $job->is_name = 0;
            $job->is_phonenumber = 0;
            $job->is_email = 0;
            $job->is_currentjob = 0;
            $job->is_previousjobs = 0;
            $job->is_description = 0;
            $job->is_verified = 0;
            $job->bonus = 0;
            $job->type_id = TypeModel::whereRaw(true)->min('id');
            $job->salary = 0;
            $job->email = $jobEmail;
            $job->phone = '';
            $job->lat = 0;
            $job->long = 0;
            $job->is_finished = 1;
            $job->salary = 0;
            $job->paid_after = '';
            $job->bonus_description = '';
            $job->is_active = 0;
            $job->is_crawled = 1;
            $job->id_crawled = $crawledId;
            $job->by_company = 0;

            $job->save();
            
            $this->sendEmailToCompany($job->id);            
        }

    }

    public function getFromCVLt ()
    {

        if ( \SH\Models\Setting::findByCode('CD08')->value == "NO") {
            return;
        }

        $category_html = HtmlDomParser::file_get_html('http://www.cv.lt');

        $catCount = 0;


        //get Categories
        $ul_html1 = $category_html->find('div#cont')[0]->find('div.HomeBlock')[0]->find('div')[0]->find('ul')[0];
        foreach ($ul_html1->find('li') as $element) {
            $name = $element->find('a')[0]->text();
            $catName = substr($name, 0, strrpos($name, '(', -1) - 1);

            $catLink = 'http://www.cv.lt'.$element->find('a')[0]->href;

            $count = CategoryModel::where('name', $catName)->get()->count();

            if ($count > 0) {
                $cat = CategoryModel::where('name', $catName)->firstOrFail();
            }else {
                $cat = new CategoryModel;

                $cat->name = $catName;
                $cat->is_crawled = 1;

                $cat->save();
            }

            $catLinks[$catCount] = $catLink;
            $catIds[$catCount] = $cat->id;
            $catCount ++;
        }


        $ul_html2 = $category_html->find('div#cont')[0]->find('div.HomeBlock')[0]->find('div')[0]->find('ul')[1];

        $index = 0;
        foreach ($ul_html2->find('li') as $element) {

            $index ++;
            if (count($ul_html2->find('li')) == $index) break;

            $name = $element->find('a')[0]->text();
            $catName = substr($name, 0, strrpos($name, '(', -1) - 1);

            $catLink = 'http://www.cv.lt'.$element->find('a')[0]->href;

            $count = CategoryModel::where('name', $catName)->get()->count();

            if ($count > 0) {
                $cat = CategoryModel::where('name', $catName)->firstOrFail();
            }else {
                $cat = new CategoryModel;

                $cat->name = $catName;
                $cat->is_crawled = 1;

                $cat->save();
            }

            $catLinks[$catCount] = $catLink;
            $catIds[$catCount] = $cat->id;
            $catCount ++;
        }



        //get Old Jobs
        $oldJobsCount = CrawledHistoryModel::where('site_id', 5)->get()->count();

        $oldJobs = '';
        if ($oldJobsCount != 0) {
            $oldJobId = CrawledHistoryModel::where('site_id', 5)->max('id');
            $cHistory = CrawledHistoryModel::find($oldJobId);
            $oldJobs = $cHistory->jobIds;
        }


        $jobCount = 0;
        $jobText = '';
        $jobNames = [];
        $jobLinks = [];
        $companyLinks = [];
        $companyNames = [];
        $jobCodes = [];
        $cityNames = [];
        $jobCatIds = [];

        //get jobs by category
        for ($i = 0; $i < $catCount; $i ++) {

            $job_html = HtmlDomParser::file_get_html($catLinks[$i]);

            $jobTables = $job_html->find('div#TablRes')[0]->find('table')[0];

            $index = 0;

            $count = 0;
            foreach ($jobTables->find('tr') as $element) {
                $index++;
                if ($index == 1) continue;
                if (count($element->find('td')) < 6) continue;


                $code = $element->id;
                $jobCode = substr($code, 1, strlen($code) - 1);
                $crawledId = '5-' . $jobCode;

                if (strlen($jobText) != 0) $jobText .= ',';
                $jobText .= $jobCode;

                if (JobModel::where('id_crawled', $crawledId)->get()->count() > 0) {
                    if (strpos($oldJobs, $jobCode) === false) {
                        $job = JobModel::where('id_crawled', $crawledId)->firstOrFail();
                        $job->status = 2;
                        $job->save();
                    }

                    continue;
                }

                $count++;
                if ($count > 20) break;

                //get basic info
                $jobLinks[$jobCount] = $element->find('td')[2]->find('p')[0]->find('meta')[2]->content;
                $jobNames[$jobCount] = $element->find('td')[2]->find('a')[0]->text();
                $companyNames[$jobCount] = $element->find('td')[2]->find('a')[1]->text();
                $cityNames[$jobCount] = $element->find('td')[2]->find('p')[0]->find('meta')[0]->content;
                $jobCodes[$jobCount] = $jobCode;
                $jobCatIds[$jobCount] = $catIds[$i];

                $jobCount ++;
            }
        }

        $crawlHistory = new CrawledHistoryModel;
        $crawlHistory->site_id = 5;
        $crawlHistory->jobIds = $jobText;
        $crawlHistory->save();

        //get jobs by category


        for ($i = 0; $i < $jobCount; $i ++) {

            $jobLink = $jobLinks[$i];
            $jobName = $jobNames[$i];
            $companyName = $companyNames[$i];
            $cityName = $cityNames[$i];
            $crawledId = '5-'.$jobCodes[$i];

            $jDetail = HtmlDomParser::file_get_html($jobLink);

            $jobContent_html = $jDetail->find('div#jobCont')[0];

            $jobEmail = '';

            if (count($jobContent_html->find('div#jobContImg')) >  0) {
                if (count($jobContent_html->find('div#jobContImg')[0]->find('img')) == 0) continue;
                $jobDes = $jobContent_html->find('div#jobContImg')[0]->find('img')[0];
                $jobDes->src = 'http://www.cv.lt'.$jobDes->src;

                if (count($jobContent_html->find('div#jobTxtRight')[0]->find('table#jobTxtRTable')) > 0) {
                    foreach ($jobContent_html->find('div#jobTxtRight')[0]->find('table#jobTxtRTable')[0]->find('script') as $scriptTag) {
                        preg_match_all("|<[^>]+>(.*)</[^>]+>|U", $scriptTag, $out,PREG_SET_ORDER);

                        if (strpos($out[0][1], 'makeMailLink') !== false) {
                            preg_match_all('/"[^"]+"/i', $out[0][1], $out1, PREG_SET_ORDER);
                            $jobEmail = substr($out1[0][0], 1, strlen($out1[0][0]) - 2).'@'.substr($out1[1][0], 1, strlen($out1[1][0]) - 2);
                            break;
                        }
                    }
                }

            }else {
                $jobDes = $jobContent_html->find('div#jobTxtRight')[0];

                foreach($jobContent_html->find('div#jobTxtRight')[0]->find('script') as $scriptTag) {
                    preg_match_all("|<[^>]+>(.*)</[^>]+>|U", $scriptTag, $out,PREG_SET_ORDER);

                    if (strpos($out[0][1], 'makeMailLink') !== false) {
                        preg_match_all('/"[^"]+"/i', $out[0][1], $out1, PREG_SET_ORDER);
                        $jobEmail = substr($out1[0][0], 1, strlen($out1[0][0]) - 2).'@'.substr($out1[1][0], 1, strlen($out1[1][0]) - 2);
                        break;
                    }
                }
            }



            $count = CityModel::where('name', $cityName)->get()->count();

            if ($count  == 0) {
                $city = new CityModel;

                $city->name = $cityName;
                $city->country_id = CountryModel::whereRaw(true)->min('id');

                $city->save();

                $city_id = $city->id;
            }else {
                $city = CityModel::where('name', $cityName)->firstOrFail();

                $city_id = $city->id;
            }


            $count = JobModel::where('is_crawled', 1)->where('id_crawled', $crawledId)->get()->count();

            if ($count == 0) {
                $job = new JobModel;
            }else {
                $job = JobModel::where('is_crawled', 1)->where('id_crawled', $crawledId)->firstOrFail();
            }

            $query = CompanyModel::where('name', $companyName);
            if ($jobEmail != '') {
                $query = $query->where('email', $jobEmail);
            }
            $count = $query->get()->count();

            if ($count ==  0) {

                $company_email = str_random(10).'@gmail.com';

                if ($jobEmail != '') $company_email = $jobEmail;

                $company = new CompanyModel;
                $company->salt = str_random(8);
                $company->secure_key = md5($company->salt . $company->salt);
                $company->teamsize_id = TeamsizeModel::whereRaw(true)->min('id');
                $company->category_id = CategoryModel::whereRaw(true)->min('id');
                $company->city_id = CityModel::whereRaw(true)->min('id');
                $company->name = $companyName;
                $company->email = $company_email;
                $company->logo = 'default_company_logo.gif';
                $company->is_admin = 1;
                $company->is_finished = 0;
                $company->is_crawled = 1;
                $company->is_spam = 1;

                $company->save();

                $company->parent_id = $company->id;
                $company->save();

                $company_id = $company->id;
            }else {
                $company = CompanyModel::where('name', $companyName)->firstOrFail();

                $company_id = $company->id;
            }

            $job->job_link = $jobLink;

            if ($jobEmail != '') {
                $jobLink = '';
            }

            $job->link_address = $jobLink;

            $job->company_id = $company_id;
            $job->name = $jobName;
            $job->level_id = LevelModel::whereRaw(true)->min('id');
            $job->description = $jobDes;
            $job->category_id = $jobCatIds[$i];
            $job->presence_id = PresenceModel::whereRaw(true)->min('id');
            $job->year = 5;
            $job->city_id = $city_id;
            $job->native_language_id = LanguageModel::whereRaw(true)->min('id');
            $job->requirements = '';
            $job->is_name = 0;
            $job->is_phonenumber = 0;
            $job->is_email = 0;
            $job->is_currentjob = 0;
            $job->is_previousjobs = 0;
            $job->is_description = 0;
            $job->is_verified = 0;
            $job->bonus = 0;
            $job->type_id = TypeModel::whereRaw(true)->min('id');
            $job->salary = 0;
            $job->email = $jobEmail;
            $job->phone = '';
            $job->lat = 0;
            $job->long = 0;
            $job->is_finished = 1;
            $job->salary = 0;
            $job->paid_after = '';
            $job->bonus_description = '';
            $job->is_active = 0;
            $job->is_crawled = 1;
            $job->id_crawled = $crawledId;
            $job->by_company = 0;

            $job->save();
            
            $this->sendEmailToCompany($job->id);            
        }
    }
    
    public function sendEmailToCompany($jobId) {
        return;
        /* if ( \SH\Models\Setting::findByCode('CD01')->value == "YES") {
            $job = JobModel::find($jobId);
            $company = CompanyModel::find($job->company_id);
            
            $email = EmailModel::findByCode('ET02');
            
            $body = str_replace('{job_link}', URL::route('user.dashboard.viewJob', ['slug' => $job->slug, 'company_id' => $company->id, ]), $email->body);
            
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
        } */
                
    }

}
