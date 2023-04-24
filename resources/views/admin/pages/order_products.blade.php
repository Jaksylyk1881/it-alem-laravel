@extends('admin.layouts.index')

@section('title', 'История заказов детальная')

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
                                Товар
                            </th>
                            <th>
                                Подарок
                            </th>
                            <th style="width: 40%">
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($order_products as $order)
                            <tr>
                                <td>
                                    {{ $order->id}}
                                </td>
                                <td>
                                    [{{$order->product->id}}] {{$order->product->name}}
                                </td>
                                <td>
                                    @if($order->gift_products?->id)
                                        [{{$order->gift_products->id}}] {{$order->gift_products->name}}
                                    @endif
                                </td>
                                <td class="text-center">
                                    <!--Review Delete -->
                                    <form action="{{route('admin.order_product.destroy',  $order->id)}}" method="POST"  class="d-inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm delete-btn" href="#">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
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
                    {!! $order_products->appends(['sort' => 'id'])->links() !!}
                </div>
            </div>
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
