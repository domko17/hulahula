<div class="card bg-transparent" id="banner_preview">
    <div class="card-body p-3">
        <div class="owl-carousel full-width">
            @foreach($banners as $banner)
                <div class="item" style="width: 100%">
                    <div class="card text-dark">
                        @if($banner->type == 1)
                            <div class="{{ $banner->getBgColorClass() }} text-white p-5"
                                 style="width: 100%; height:250px">
                                <div style="position: relative; top: 35%;">
                                    @if($banner->ext_link)
                                        <a href="{{ $banner->ext_link }}" class="text-white">
                                            @endif
                                            <h3 class="text-center">{{ $banner->title }}</h3>
                                            <h6 class="card-text mb-4 font-weight-normal text-center"
                                            >{{ $banner->description }}</h6>
                                            @if($banner->ext_link)
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @elseif($banner->type == 2)
                            <div id="prev_type_2" class="bg-gradient-primary text-white p-5"
                                 style="width: 100%; height:250px;
                                     background-size: cover;
                                 @if($banner->image)
                                     background-image: url({{ asset('images/banners/'.$banner->image)}});
                                 @else
                                     background-image: url({{ asset('images/app/AuthBackgrounds/auth_bckg_1.jpg')}});
                                 @endif
                                     ">
                                <div style="position: relative; top: 70%">
                                    @if($banner->ext_link)
                                        <a href="{{ $banner->ext_link }}" class="text-white">
                                            @endif
                                            <h3 class="text-center">{{ $banner->title }}</h3>
                                            <h6 class="card-text mb-4 font-weight-normal text-center"
                                            >{{ $banner->description }}</h6>
                                            @if($banner->ext_link)
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @elseif($banner->type == 3)
                            <div class="bg-gradient-primary text-white row mx-1"
                                 style="width: 100%; height:250px;">

                                <iframe height="250" style="width: 50%;"
                                        src="{{ $banner->url }}"
                                        frameborder="0" id="prev_yt_iframe" class="col-6"
                                        allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen></iframe>
                                <div class="col-6">
                                    <div style="position: relative; top: 35%;">
                                        @if($banner->ext_link)
                                            <a href="{{ $banner->ext_link }}" class="text-white">
                                                @endif
                                                <h3 class="text-center">{{ $banner->title }}</h3>
                                                <h6 class="card-text mb-4 font-weight-normal text-center"
                                                >{{ $banner->description }}</h6>
                                                @if($banner->ext_link)
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
