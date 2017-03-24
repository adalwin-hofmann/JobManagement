@extends('user.layout')

@section('body')
<main>
    <div class="background-consumerBasic">
        <img src="/assets/img/background_consumerBasic.jpg" style="visibility:hidden; width: 100%"/>

        <div class="page-title-box" style="top: 63%;">
            <p class="p-page-title margin-top-xs">{{ trans('user.text_15') }}</p>
            <p class="p-page-title-description margin-top-xs margin-bottom-xs" style="font-size: 22px;">{{ trans('user.text_16') }}</p>
        </div>

        <div class="page-title-button-box">
            <a class="btn btn-explain">{{ trans('user.sign_up') }}</a>
        </div>
    </div>





    <div class="div-aboutUs-content">
        <div class="container margin-top-sm">
            <div class="row margin-top-sm text-center">
                <p class="bitter-font bold-weight color-black" style="font-size: 30px;">{{ trans('user.text_17') }}</p>
                <p class="ptserif-font italic-style bold-weight color-black" style="font-size: 17px;">{{ trans('user.text_18') }}</p>
            </div>
            <div class="row margin-bottom-sm margin-top-xs">
                <div class="col-sm-4 text-center">
                    <div class="col-sm-10 col-sm-offset-2 content-box color-black">
                        <p class="bitter-font bold-weight margin-top-lg" style="font-size: 24px;"> {{ trans('user.text_19') }}</p>
                        <p class="robot-font margin-top-xs" style="font-size: 15px;">{{ trans('user.text_20') }}</p><br>
                        <p class="robot-font" style="font-size: 15px; margin-bottom: 5px;"><b>{{ trans('user.text_21') }}</b></p>
                        <p class="robot-font" style="font-size: 15px; margin-bottom: 5px;"><b>{{ trans('user.text_22') }}</b></p>
                        <p class="robot-font" style="font-size: 15px; margin-bottom: 5px;"><b>{{ trans('user.text_23') }}</b></p>
                        <p class="robot-font" style="font-size: 15px; margin-bottom: 5px;"><b>{{ trans('user.text_24') }}</b></p>
                    </div>
                </div>

                <div class="col-sm-4 text-center">
                    <div class="col-sm-10 col-sm-offset-1 content-box color-black">
                        <p class="bitter-font bold-weight margin-top-lg" style="font-size: 24px;"> {{ trans('user.text_25') }}</p>
                        <p class="robot-font margin-top-xs" style="font-size: 15px;">{{ trans('user.text_26') }}</p><br>
                        <p class="robot-font" style="font-size: 15px;">{{ trans('user.text_27') }}</p>
                        <p class="robot-font" style="font-size: 15px;">{{ trans('user.text_28') }}</p>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="col-sm-10 content-box text-center color-black">
                        <p class="bitter-font bold-weight margin-top-lg" style="font-size: 24px;"> {{ trans('user.text_29') }}</p>
                        <p class="robot-font margin-top-xs" style="font-size: 15px;">{{ trans('user.text_30') }}</p><br/>
                        <p class="robot-font margin-top-xs" style="font-size: 15px;">{{ trans('user.text_31') }}</p>
                    </div>
                </div>


            </div>
        </div>
    </div>

    <div style="background: #4d6bc9;">
        <div class="container">
            <div class="row margin-top-sm">
                <div class="col-sm-12">
                    <div class="col-sm-5 consumer-basic-map-image">
                        <img src="/assets/img/consumer_map.jpg" style="visibility:hidden; width: 100%"/>
                    </div>
                    <div class="col-sm-6 col-sm-offset-1 text-center margin-top-lg">
                        <p class="ptserif-font color-white italic-style" style="font-size: 30px;">{{ trans('user.text_32') }}</p>
                        <p class="droidSerif-font color-white" style="font-size: 15px">{{ trans('user.text_33') }}</p>
                        <p class="droidSerif-font color-white" style="font-size: 15px">{{ trans('user.text_34') }}</p>
                    </div>
                </div>
            </div>

            <div class="row margin-top-sm margin-bottom-sm">
                <div class="col-sm-12">
                    <div class="col-sm-6 text-center margin-top-lg">
                        <p class="ptserif-font color-white italic-style" style="font-size: 30px;">{{ trans('user.text_35') }}</p>
                        <p class="droidSerif-font color-white" style="font-size: 15px; margin-bottom: 0px;">{{ trans('user.text_36') }}</p>
                        <p class="droidSerif-font color-white" style="font-size: 15px">{{ trans('user.text_37') }}</p><br/>
                        <p class="droidSerif-font color-white" style="font-size: 15px">{{ trans('user.text_38') }}</p>
                    </div>
                    <div class="col-sm-5 col-sm-offset-1 consumer-basic-table-image">
                        <img src="/assets/img/consumer_image_table.jpg" style="visibility:hidden; width: 100%"/>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="div-aboutUs-content">
        <div class="container margin-top-sm">
            <div class="row margin-top-sm text-center">
                <p class="bitter-font bold-weight color-black" style="font-size: 30px;">{{ trans('user.text_39') }}</p>
            </div>

            <div class="row margin-top-lg text-center">
                <div class="col-sm-6 col-sm-offset-3">
                    <div class="col-sm-2 text-center">
                        <div class="diamond background-white text-center">
                            <p class="ptserif-font italic-style bold-weight p-diamond-number">1</p>
                        </div>
                    </div>
                    <div class="col-sm-3 text-center">
                        <div class="diamond-line background-white" style="margin-top: 19px;"></div>
                    </div>
                    <div class="col-sm-2 text-center">
                        <div class="diamond background-white text-center">
                            <p class="ptserif-font italic-style bold-weight p-diamond-number">2</p>
                        </div>
                    </div>
                    <div class="col-sm-3 text-center">
                        <div class="diamond-line background-white" style="margin-top: 19px;"></div>
                    </div>
                    <div class="col-sm-2 text-center">
                        <div class="diamond background-white text-center">
                            <p class="ptserif-font italic-style bold-weight p-diamond-number">3</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row margin-top-xs margin-bottom-normal">
                <div class="col-sm-6 col-sm-offset-3">
                    <div class="col-sm-2 text-center">
                        <div class="row">
                            <p class="ptserif-font italic-style row" style="font-size: 18px;">{{ trans('user.text_40') }}</p>
                        </div>
                    </div>
                    <div class="col-sm-2 text-center col-sm-offset-3">
                        <div class="row">
                            <p class="ptserif-font italic-style row" style="font-size: 18px;">{{ trans('user.text_41') }}</p>
                        </div>
                    </div>
                    <div class="col-sm-2 text-center col-sm-offset-3">
                        <div class="row">
                            <p class="ptserif-font italic-style row" style="font-size: 18px;">{{ trans('user.text_42') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="background-consumer-footer">
        <img src="/assets/img/background_consumer_footer.jpg" style="visibility:hidden; width: 100%"/>

        <div class="page-footer-title-box">
            <p class="bitter-font bold-weight color-black" style="font-size: 40px; text-shadow: 0 0 10px white;">{{ trans('user.text_43') }}</p>
        </div>

        <div class="page-title-box padding-top-xs padding-bottom-xs" style="top: 46%;">
            <p class="p-page-title margin-top-xs" style="font-size: 33px;">{{ trans('user.text_44') }}</p>
            <p class="p-page-title-description margin-top-xs" style="font-size: 22px; margin-bottom: 0px;">{{ trans('user.text_45') }}</p>
            <p class="p-page-title-description margin-bottom-xs" style="font-size: 22px;">{{ trans('user.text_46') }}</p>

            <a class="btn btn-explain margin-top-normal margin-bottom-sm">{{ trans('user.register') }}</a>
        </div>

    </div>
</main>
@stop

@stop