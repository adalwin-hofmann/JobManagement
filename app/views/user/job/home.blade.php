@extends('user.layout')

@section('body')
<main class="bs-docs-masthead div-front-background-img" role="main">
    <div class="container search-box margin-bottom-sm">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1 margin-top-xl padding-bottom-normal" style="background: rgba(0, 157, 255,0.65);">
            
                <div class="row">
                    <div class="col-sm-12 text-center margin-top-normal">
                        <h2 class="color-white">
                            <i>Get Hired. Or Earn Money Recruiting.</i>
                        </h2>
                    </div>
                </div>
                
                <div class="row margin-top-normal">
                    <form class="form-horizontal" method="post" action="{{ URL::route('user.job.search') }}">
                        <div class="col-sm-5 col-sm-offset-1">
                            <input type="text" name="keyword" class="form-control input-lg" placeholder="Job title, keyword or company"/>
                        </div>
                        <div class="col-sm-4">
					        <select class="form-control input-lg" name="city_id">
                                <option value = "0">All Location</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}">{{ $city->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-1">
                            <button class="btn blue btn-lg btn-block" style="background: #009CFF;"><i class="fa fa-search"></i></button>
                        </div>
                    </form>
                </div>
                
                <div class="row margin-top-normal">
                    <div class="col-sm-12 text-center">
                        <h4 class="color-white"><i>We currently feature X jobs, to which X can be applied for straight from our platform</i></h4>
                    </div>
                </div>                
                
            </div>
        </div>
    </div>
</main>

<div class="container">
    <div class="row margin-top-lg">
        <div class="col-sm-12 color-blue text-center"><h2><i>Where next career move begins</i></h2></div>
    </div>
    
    <div class="row margin-top-sm">
        <div class="col-sm-4 text-center">
            <h5>
                <p>
                    <i class="fa fa-search"></i>&nbsp;<b>Search for thousands of jobs</b>
                </p>
                <p class="padding-left-20">Find all opening from leading</p>
                <p class="padding-left-20">job portals in your country</p>
            </h5>
        </div>
        
        <div class="col-sm-4 text-center">
            <h5>
                <p>
                    <i class="fa fa-envelope-o"></i>&nbsp;<b>Apply for jobs without leaving the site</b>
                </p>
                <p class="padding-left-20">You can apply for openings easily by using your</p>
                <p class="padding-left-20">premade templates. Applying has never been so easy</p>                        
            </h5>
        </div>
        
        <div class="col-sm-4 text-center">
            <h5>
                <p>
                    <i class="fa fa-check"></i>&nbsp;<b>Get hired faster and easier than ever</b>
                </p>
                <p class="padding-left-20">Track application opening and manage your</p>
                <p class="padding-left-20">applications and get notified for new openings</p>                        
            </h5>
        </div> 
    </div>
</div>

<div class="home-middle margin-top-lg">
    <div class="container">
        <div class="row margin-top-lg">
            <div class="col-sm-12 text-center">
                <h2 class="color-white"><i>Why should you start using Jobella?</i></h2>
            </div>
        </div>
        
        <div class="row margin-top-normal">
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-6 col-xs-12 text-center">
                        <div class="home-middle-item">
                            <i class="fa fa-globe font-size-40"></i>
                        </div>
                        <p class="color-white padding-top-sm"><b>All jobs in one portal</b></p>
                        <p class="color-white padding-top-xs">Jobella brings up all the local and global</p>
                        <p class="color-white">jobs available in one platform to save you</p>
                        <p class="color-white">time and make application process easier</p>
                        <p class="color-white">for you</p>
                    </div>
                    
                    <div class="col-sm-6 col-xs-12 text-center">
                        <div class="home-middle-item">
                            <i class="fa fa-envelope-o font-size-40"></i>
                        </div>
                        <p class="color-white padding-top-sm"><b>Tracking openings</b></p>
                        <p class="color-white padding-top-xs">After sending applications you can track</p>
                        <p class="color-white">if the recruiteres are opening your</p>
                        <p class="color-white">applications and get noticed once your</p>
                        <p class="color-white">application is opened.</p>
                    </div>                
                </div>
            </div>
            
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-6 col-xs-12 text-center">
                        <div class="home-middle-item">
                            <i class="fa fa-euro font-size-40"></i>
                        </div>
                        <p class="color-white padding-top-sm"><b>Easy money</b></p>
                        <p class="color-white padding-top-xs">A easy way to monetize your connection</p>
                        <p class="color-white">network! Recomment a job to your friend</p>
                        <p class="color-white">and if he gets hired you receive a</p>
                        <p class="color-white">recruitment bonus!</p>
                    </div>
                    
                    <div class="col-sm-6 col-xs-12 text-center">
                        <div class="home-middle-item">
                            <i class="fa fa-clock-o font-size-40"></i>
                        </div>
                        <p class="color-white padding-top-sm"><b>Faster applying process</b></p>
                        <p class="color-white padding-top-xs">Through application templates</p>
                        <p class="color-white">management you can easily create</p>
                        <p class="color-white">templates which you can use and edit </p>
                        <p class="color-white">fast before sending application.</p>
                    </div>                
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row margin-top-lg">
        <div class="col-sm-12 color-blue text-center"><h2><i>Most popular categories</i></h2></div>
    </div>
    <div class="row margin-bottom-lg">
        @foreach ($categories as $value)
        <div class="col-sm-2 col-xs-6 margin-top-sm">
            <div class="text-center padding-top-normal home-category-item color-blue" data-id="{{ $value->id }}" style="background: url({{ HTTP_CATEGORY_PATH.$value->photo }});">
            {{ $value->name." (".$value->cnt.")" }}
            </div>
        </div>
        @endforeach
    </div>
    
    <div class="row margin-top-lg">
        <div class="col-sm-12 color-blue text-center"><h2><i>Earn money by recommending people you know</i></h2></div>
    </div>

    <div class="row margin-top-normal margin-bottom-lg">
        <div class="col-sm-4 text-center">
            <h5>
                <p>
                    <i class="fa fa-search"></i>&nbsp;<b>Find a job with a referral bonus</b>
                </p>
                <p class="padding-left-20">look for a interneting position from</p>
                <p class="padding-left-20">a company offering referral bonus</p>
            </h5>
        </div>
        
        <div class="col-sm-4 text-center">
            <h5>
                <p>
                    <i class="fa fa-envelope-o"></i>&nbsp;<b>Apply with a referral or refer someone</b>
                </p>
                <p class="padding-left-20">Get someone to vouch for you, get noticed</p>
                <p class="padding-left-20">and share the rewards when you get hired</p>                        
            </h5>
        </div>
        
        <div class="col-sm-4 text-center">
            <h5>
                <p>
                    <i class="fa fa-check"></i>&nbsp;<b>Get hired faster and easier than ever</b>
                </p>
                <p class="padding-left-20">We're placing amazing candidates in</p>
                <p class="padding-left-20">amazing companies. Keep spreading the word.</p>                        
            </h5>
        </div> 
    </div>        
</div>

<div class="home-bottom margin-top-lg">
    <div class="container">
        <div class="row margin-top-lg">
            <div class="col-sm-12 text-center">
                <h2 class="color-white"><i>Do you know someone qualifed for?</i></h2>
            </div>
        </div>
        <div class="row margin-bottom-sm">
            @foreach ($jobs as $job)
            <div class="col-sm-3 col-xs-6 margin-top-sm">
                <div class="color-blue text-center font-size-sm home-bonus-job-item">
                    <div class="refer-name-item">{{ $job->name }}</div>
                    <div class="refer-city-item"><b>{{ $job->company->name.", ".$job->city->name }}</b></div>
                    <div><b>{{ $job->bonus."&euro;" }}</b></div>
                    <div class="margin-top-xs"><a href="{{ URL::route('user.dashboard.viewJob', $job->slug) }}" class="btn blue btn-sm btn-block">Refer</a></div>                    
                </div>
            </div>
            @endforeach
        </div>
        
    </div>
</div>

<form class="form-horizontal" method="post" action="{{ URL::route('user.job.search') }}" id="search_form">
    <input type="hidden" name="category_id"/>
</form>

@stop

@section('custom-scripts')
	@include('js.user.job.home')
@stop