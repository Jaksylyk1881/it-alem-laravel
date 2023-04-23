@extends('admin.layouts.index')

@section('title', 'Комментарий')

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
                            <th style="width: 40%">
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($reviews as $review)
                            <tr>
                                <td>
                                    {{ $review->id}}
                                </td>
                                <td>
                                    {{$review->user->name}}
                                </td>
                                <td class="text-center">
                                    <!-- Button trigger Review Edit -->
                                    <a href="#"  type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#ReviewEdit{{$review->id}}">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <!-- Form Review Edit -->
                                    <div class="modal fade" id="ReviewEdit{{$review->id}}" tabindex="-3" aria-labelledby="ReviewEdit{{$review->id}}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Редактировать</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body ">
                                                    <div class="input-group mb-2 col-lg-18">
                                                        <label>Продукт</label>
                                                        <input type="text" class="form-control" name="name"  placeholder="Введите " value="{{$review->product->name}}" aria-describedby="basic-addon1">
                                                    </div>
                                                    <div class="input-group mb-2 col-lg-18">
                                                        <label>Пользователь</label>
                                                        <input type="text" class="form-control" name="price"  placeholder="Введите  " value="{{$review->user->name}}" aria-describedby="basic-addon1">
                                                    </div>
                                                    <div class="input-group mb-2 col-lg-18">
                                                        <label>Оценка</label>
                                                        <input type="text" class="form-control" name="count"  placeholder="Введите  " value="{{$review->rate}}" aria-describedby="basic-addon1">
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="description">Текст</label>
                                                        <textarea name="description" id="description" class="form-control">{{$review->body}}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!--Review Delete -->
                                    <form action="{{route('admin.product.review.destroy', [request('product'), $review->id])}}" method="POST"  class="d-inline-block">
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
                    {!! $reviews->appends(['sort' => 'id'])->links() !!}
                </div>
            </div>
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
