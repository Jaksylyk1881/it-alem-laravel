@extends('admin.layouts.index')

@section('title', 'История заказов')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body p-0">
                    <table class="table table-striped projects">
                        <thead>
                        <tr>
                            <th style="width: 5%">
                                ID
                            </th>
                            <th>
                                Пользователь
                            </th>
                            <th>
                                Магазин
                            </th>
                            <th>
                                Время
                            </th>
                            <th style="width: 40%">
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td>
                                    {{ $order->id}}
                                </td>
                                <td>
                                    {{$order->user->name}}
                                </td>
                                <td>
                                    {{$order->products[0]->product->user->name}}
                                </td>
                                <td>
                                    {{$order->created_at}}
                                </td>
                                <td class="text-center">
                                    <!-- Button trigger Review Edit -->
                                    <a href="#"  type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#ReviewEdit{{$order->id}}">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <!-- Form Review Edit -->
                                    <div class="modal fade" id="ReviewEdit{{$order->id}}" tabindex="-3" aria-labelledby="ReviewEdit{{$order->id}}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Редактировать</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body ">
                                                    <div class="input-group mb-2 col-lg-18">
                                                        <label>Адресс</label>
                                                        <input type="text" class="form-control" name="name"  placeholder="Введите " value="{{$order->address->name}}" aria-describedby="basic-addon1">
                                                    </div>
                                                    <div class="input-group mb-2 col-lg-18">
                                                        <label>Пользователь</label>
                                                        <input type="text" class="form-control" name="price"  placeholder="Введите  " value="{{$order->user->name}}" aria-describedby="basic-addon1">
                                                    </div>
                                                    <div class="  mb-2 col-lg-18 offset-lg-18">
                                                        <p style="padding: 5px"></p>
                                                        <label >Тип</label>
                                                        <select name="type" id="" style="color: #232c4d">
                                                            <option value="1" @selected($order->delivery_type == 'pickup')>pickup</option>
                                                            <option value="2" @selected($order->delivery_type == 'delivery')>delivery</option>
                                                        </select>
                                                    </div>
                                                    <div class="  mb-2 col-lg-18 offset-lg-18">
                                                        <p style="padding: 5px"></p>
                                                        <label >Тип</label>
                                                        <select name="type" id="" style="color: #232c4d">
                                                            <option value="1" @selected($order->payment_type == 'cash')>cash</option>
                                                            <option value="2" @selected($order->payment_type == 'card')>card</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="description">Текст</label>
                                                        <textarea name="description" id="description" class="form-control">{{$order->description}}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!--Review Delete -->
                                    <form action="{{route('admin.order.destroy',  $order->id)}}" method="POST"  class="d-inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm delete-btn" href="#">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    <a href="{{route('admin.order_product.index', $order->id)}}"  type="button" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-th"></i>
                                        Товары/Услуги
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <div class="switch" style="padding: 30px 0;text-align: center;">
                <div class="switch-nav" style="margin: 0 auto;display: table;">
                    {!! $orders->appends(['sort' => 'id'])->links() !!}
                </div>
            </div>
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
