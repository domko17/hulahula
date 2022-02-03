@extends('layouts.app')

@section('content')
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center text-center error-page bg-primary">
                <div class="row flex-grow">
                    <div class="col-lg-7 mx-auto text-white">
                        <div class="row align-items-center d-flex flex-row">
                            <div class="col-12 col-md-6 text-lg-right pr-lg-4">
                                <h1 class="display-1 mb-0"><i class="mdi mdi-lan-disconnect"></i></h1>
                            </div>
                            <div class="col-lg-6 error-page-divider text-lg-left pl-lg-4">
                                <h2>Bez pripojenia</h2>
                                <h3 class="font-weight-light">Vaše zariadenie momentálne nemá prístup k internetu</h3>
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-12 text-center mt-xl-2">
                                Skontrolujte si Vaše pripojenie a skúste to znovu
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
