@extends('user.layout')

@section('body')
<main>
    <div class="background-consumers">
        <img src="/assets/img/background_consumers.jpg" style="visibility:hidden; width: 100%"/>

        <div class="page-title-box" style="top: 63%;">
            <p class="p-page-title margin-top-xs">{{ trans('user.text_75') }}</p>
            <p class="p-page-title-description margin-top-xs margin-bottom-xs" style="font-size: 22px;">{{ trans('user.text_76') }}</p>
        </div>

        <div class="page-title-button-box">
            <a class="btn btn-explain">{{ trans('user.sign_up') }}</a>
        </div>
    </div>

    <div class="div-aboutUs-content">
        <div class="container margin-top-sm">
            <div class="row margin-top-sm text-center">
                <p class="bitter-font bold-weight color-black" style="font-size: 30px;">{{ trans('user.text_77') }}</p>
                <p class="ptserif-font italic-style bold-weight color-black" style="font-size: 17px;">{{ trans('user.text_78') }}</p>
            </div>
            <div class="row margin-bottom-sm margin-top-xs">
                <div class="col-sm-4 text-center">
                    <div class="col-sm-10 col-sm-offset-2 content-box color-black">
                        <p class="bitter-font bold-weight margin-top-normal" style="font-size: 24px;"> {{ trans('user.text_79') }}</p>
                        <p class="robot-font margin-top-xs" style="font-size: 15px;">{{ trans('user.text_80') }}</p><br>
                        <p class="robot-font" style="font-size: 15px;">{{ trans('user.text_81') }}</p><br/>
                        <p class="robot-font" style="font-size: 15px;">{{ trans('user.text_82') }}</p>
                    </div>
                </div>

                <div class="col-sm-4 text-center">
                    <div class="col-sm-10 col-sm-offset-1 content-box color-black">
                        <p class="bitter-font bold-weight margin-top-normal" style="font-size: 24px;"> {{ trans('user.text_83') }}</p>
                        <p class="robot-font margin-top-xs" style="font-size: 15px;">{{ trans('user.text_84') }}</p><br>
                        <p class="robot-font" style="font-size: 15px;">{{ trans('user.text_85') }}</p><br/>
                        <p class="robot-font" style="font-size: 15px;">{{ trans('user.text_86') }}</p>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="col-sm-10 content-box text-center color-black">
                        <p class="bitter-font bold-weight margin-top-normal" style="font-size: 24px;"> {{ trans('user.text_87') }}</p>
                        <p class="robot-font margin-top-xs" style="font-size: 15px;">{{ trans('user.text_88') }}</p><br/>
                        <p class="robot-font" style="font-size: 15px;">{{ trans('user.text_89') }}</p><br/>
                        <p class="robot-font" style="font-size: 15px;">{{ trans('user.text_90') }}</p>
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
                        <p class="ptserif-font color-white italic-style" style="font-size: 30px;">{{ trans('user.text_91') }}</p>
                        <p class="droidSerif-font color-white" style="font-size: 15px">{{ trans('user.text_92') }}</p>
                    </div>
                </div>
            </div>

            <div class="row margin-top-sm">
                <div class="col-sm-12">
                    <div class="col-sm-6 text-center margin-top-lg">
                        <p class="ptserif-font color-white italic-style" style="font-size: 30px;">{{ trans('user.text_93') }}</p>
                        <p class="droidSerif-font color-white" style="font-size: 15px; margin-bottom: 0px;">{{ trans('user.text_94') }}</p><br/>
                        <p class="droidSerif-font color-white" style="font-size: 15px">{{ trans('user.text_95') }}</p>
                    </div>
                    <div class="col-sm-5 col-sm-offset-1 consumer-basic-table-image">
                        <img src="/assets/img/consumer_image_table.jpg" style="visibility:hidden; width: 100%"/>
                    </div>
                </div>
            </div>

            <div class="row margin-top-sm">
                <div class="col-sm-12">
                    <div class="col-sm-5 consumer-basic-map-image">
                        <img src="/assets/img/consumer_map.jpg" style="visibility:hidden; width: 100%"/>
                    </div>
                    <div class="col-sm-6 col-sm-offset-1 text-center margin-top-lg">
                        <p class="ptserif-font color-white italic-style" style="font-size: 30px;">{{ trans('user.text_96') }}</p>
                        <p class="droidSerif-font color-white" style="font-size: 15px">{{ trans('user.text_97') }}</p><br/>
                        <p class="droidSerif-font color-white" style="font-size: 15px">{{ trans('user.text_98') }}</p>
                    </div>
                </div>
            </div>


            <div class="row margin-top-sm margin-bottom-sm">
                <div class="col-sm-12">
                    <div class="col-sm-6 text-center margin-top-lg">
                        <p class="ptserif-font color-white italic-style" style="font-size: 30px;">{{ trans('user.text_99') }}</p>
                        <p class="droidSerif-font color-white" style="font-size: 15px; margin-bottom: 0px;">{{ trans('user.text_100') }}</p><br/>
                        <p class="droidSerif-font color-white" style="font-size: 15px; margin-bottom: 0px;">{{ trans('user.text_101') }}</p>
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
                            <p class="ptserif-font italic-style row" style="font-size: 18px;">{{ trans('user.text_102') }}</p>
                        </div>
                    </div>
                    <div class="col-sm-2 text-center col-sm-offset-3">
                        <div class="row">
                            <p class="ptserif-font italic-style row" style="font-size: 18px;">{{ trans('user.text_103') }}</p>
                        </div>
                    </div>
                    <div class="col-sm-2 text-center col-sm-offset-3">
                        <div class="row">
                            <p class="ptserif-font italic-style row" style="font-size: 18px;">{{ trans('user.text_104') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="background-feature-business-footer">
        <img src="/assets/img/background_feature_business_footer.jpg" style="visibility:hidden; width: 100%"/>

        <div class="page-footer-title-box" style="top: 15%;">
            <div class="col-sm-8 col-sm-offset-2">
                <p class="bitter-font bold-weight color-black" style="font-size: 40px; text-shadow: 0 0 10px white;">{{ trans('user.text_105') }}</p>
            </div>
        </div>

        <div class="page-title-box padding-top-xs padding-bottom-xs" style="top: 54%; bottom: 0;">
            <div class="col-sm-10 col-sm-offset-1">
                <p class="p-page-title margin-top-xs" style="font-size: 33px;">{{ trans('user.text_106') }}</p>
                <p class="p-page-title-description margin-top-xs" style="font-size: 22px; margin-bottom: 0px;">{{ trans('user.text_107') }}</p>

                <a class="btn btn-explain margin-top-normal margin-bottom-sm">{{ trans('user.register') }}</a>
            </div>
        </div>

    </div>
</main>
@stop

@stop