@extends('app')

@section('main-content')
    <div class="position-relative">
        @if (isset($subscriptionresult))
            <div class="alert alert-info">
                <strong>Suscription to plan: {{ $plan }} done!<br>
            </div>
        @endif
    </div>
    <!-- /.position-relative -->

    <div class="row">
        <!-- #section:pages/pricing.large -->
        <div class="col-xs-6 col-sm-3 pricing-box"/>

    </div>

    <div class="col-xs-6 col-sm-3 pricing-box">
        <div class="widget-box widget-color-orange">
            <div class="widget-header">
                <h5 class="widget-title bigger lighter">Basic Package</h5>
            </div>

            <div class="widget-body">
                <div class="widget-main">
                    <ul class="list-unstyled spaced2">
                        <li>
                            <i class="ace-icon fa fa-check green"></i>
                            50 GB Disk Space
                        </li>

                        <li>
                            <i class="ace-icon fa fa-check green"></i>
                            1 TB Bandwidth
                        </li>

                        <li>
                            <i class="ace-icon fa fa-check green"></i>
                            1000 Email Accounts
                        </li>

                        <li>
                            <i class="ace-icon fa fa-check green"></i>
                            100 MySQL Databases
                        </li>

                        <li>
                            <i class="ace-icon fa fa-check green"></i>
                            $25 Ad Credit
                        </li>

                        <li>
                            <i class="ace-icon fa fa-check green"></i>
                            Free Domain
                        </li>
                    </ul>

                    <hr />
                    <div class="price">
                        $5
                        <small>/month</small>
                    </div>
                </div>

                <div>
                    <div class="btn btn-block btn-warning">
                        <form action="" method="POST">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="plan" value="basic">
                            <script
                                    src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                    data-key="pk_test_K19paoH5iQhKhLqvxt5STeRm"
                                    data-amount="500"
                                    data-name="basic"
                                    data-description="Basic Package ($5.00)"
                                    data-image="/img/Opml-icon-128x128.png">
                            </script>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xs-6 col-sm-3 pricing-box">
        <div class="widget-box widget-color-blue">
            <div class="widget-header">
                <h5 class="widget-title bigger lighter">Starter Package</h5>
            </div>

            <div class="widget-body">
                <div class="widget-main">
                    <ul class="list-unstyled spaced2">
                        <li>
                            <i class="ace-icon fa fa-check green"></i>
                            200 GB Disk Space
                        </li>

                        <li>
                            <i class="ace-icon fa fa-check green"></i>
                            Unlimited Bandwidth
                        </li>

                        <li>
                            <i class="ace-icon fa fa-check green"></i>
                            1000 Email Accounts
                        </li>

                        <li>
                            <i class="ace-icon fa fa-check green"></i>
                            200 MySQL Databases
                        </li>

                        <li>
                            <i class="ace-icon fa fa-check green"></i>
                            $25 Ad Credit
                        </li>

                        <li>
                            <i class="ace-icon fa fa-check green"></i>
                            Free Domain
                        </li>
                    </ul>

                    <hr />
                    <div class="price">
                        $10
                        <small>/month</small>
                    </div>
                </div>

                <div>
                    <div class="btn btn-block btn-primary">
                        <form action="" method="POST">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="plan" value="starter">
                            <script
                                    src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                    data-key="pk_test_K19paoH5iQhKhLqvxt5STeRm"
                                    data-amount="1000"
                                    data-name="starter"
                                    data-description="Starter Package ($10.00)"
                                    data-image="/img/Opml-icon-128x128.png">
                            </script>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xs-6 col-sm-3 pricing-box">

    </div>

    <!-- /section:pages/pricing.large -->
    </div>

@endsection
