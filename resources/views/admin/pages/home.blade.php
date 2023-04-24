@extends('admin.layouts.index')

@section('title', 'Главная')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">

                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{$product_count ?? ''}}</h3>

                            <p>Товаров/Услуг</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-th"></i>
                        </div>
                        <a href="{{route('admin.product.index')}}" class="small-box-footer">Подробнее <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-gradient-info">
                        <div class="inner">
                            <h3>{{$user_count ?? ''}}</h3>

                            <p>Пользователей</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-user"></i>
                        </div>
                        <a href="{{route('admin.user.index')}}" class="small-box-footer">Подробнее<i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-secondary">
                        <div class="inner">
                            <h3>{{$order_count ?? ''}}</h3>
                            <p>Заказов</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-phone"></i>
                        </div>
                        <a href="{{route('admin.order.index')}}" class="small-box-footer">Подробнее<i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
            <!-- /.row -->
            <!-- Main row -->

            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
