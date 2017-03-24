@extends('user.layout')

@section('body')
<main>
    <div class="background-about">
        <img src="/assets/img/background_aboutUs.jpg" style="visibility:hidden; width: 100%"/>

        <div class="page-title-box">
            <p class="p-page-title margin-top-xs">{{ trans('user.socialheadhunter') }}</p>
            <p class="p-page-title-description margin-bottom-xs">{{ trans('user.text_07') }}</p>
        </div>
    </div>

    <div class="div-aboutUs-content">
        <div class="container">
            <div class="row margin-top-sm text-center">
                <p class="robot-font bold-weight" style="font-size: 24px;">{{ trans('user.text_08') }}</p>
                <p class="ptserif-font italic-style bold-weight" style="font-size: 17px;">{{ trans('user.text_09') }}</p>
            </div>
            <div class="row margin-bottom-sm margin-top-xs">
                <div class="col-sm-4 text-center">
                    <div class="col-sm-10 col-sm-offset-2 content-box">
                        <p class="robot-font bold-weight margin-top-lg" style="font-size: 24px;"> {{ trans('user.mission') }}</p>
                        <p class="robot-font margin-top-xs" style="font-size: 15px;">{{ trans('user.text_10') }}</p><br>
                        <p class="robot-font" style="font-size: 15px;">{{ trans('user.text_13') }}</p>
                    </div>
                </div>

                <div class="col-sm-4 text-center">
                    <div class="col-sm-10 col-sm-offset-1 content-box">
                        <p class="robot-font bold-weight margin-top-lg" style="font-size: 24px;"> {{ trans('user.text_08') }}</p>
                        <p class="robot-font margin-top-xs" style="font-size: 15px;">{{ trans('user.text_11') }}</p><br>
                        <p class="robot-font" style="font-size: 15px;">{{ trans('user.text_14') }}</p>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="col-sm-10 content-box text-center">
                        <p class="robot-font bold-weight margin-top-lg" style="font-size: 24px;"> {{ trans('user.contact_us') }}</p>
                        <p class="robot-font margin-top-xs" style="font-size: 15px;">{{ trans('user.text_12') }}</p>
                        <p class="robot-font margin-top-xs" style="font-size: 15px;">Linnatullinkatu 1 a 140</p>
                        <p class="robot-font margin-top-xs" style="font-size: 15px;">02600 Espoo</p><br/>
                        <p class="robot-font margin-top-xs" style="font-size: 15px;">045 2625977</p>
                        <p class="robot-font margin-top-xs" style="font-size: 15px;">info@finternet-group.com</p>
                    </div>
                </div>


            </div>
        </div>
    </div>
</main>
@stop

@stop