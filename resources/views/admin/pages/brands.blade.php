@extends('admin.layouts.index')

@section('title', 'Бренды')

@section('top_right_content')
    <div class="offset-lg-10 btn-lg">
        <a type="button"  data-bs-toggle="modal" data-bs-target="#AddNewBrand">
            Создать
        </a>
    </div>

    <!--Add form new brand-->
    <div class="modal fade" id="AddNewBrand" tabindex="-3" aria-labelledby="AddNewBrand" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Добавьте пользаветеля</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ">
                    <form method="post"  enctype="multipart/form-data"  action="{{route('admin.brand.store')}}">
                        @csrf
                        <div class="input-group mb-2 col-lg-18">
                            <input type="text" class="form-control" name="name"  placeholder="Введите имя " aria-describedby="basic-addon1" required>
                        </div>
                        <div class="align-content-end input-group mb-2 col-lg-18 offset-lg-8  ">
                            <button type="submit" class=" btn-success">Добавить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

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
                                Название
                            </th>
                            <th style="width: 40%">
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($brands as $brand)
                            <tr>
                                <td>
                                    {{ $brand->id}}
                                </td>
                                <td>
                                    {{$brand->name}}
                                </td>
                                <td class="text-center">
                                    <!-- Button trigger Brand Edit -->
                                    <a href="#"  type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#BrandEdit{{$brand->id}}">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <!-- Form Brand Edit -->
                                    <div class="modal fade" id="BrandEdit{{$brand->id}}" tabindex="-3" aria-labelledby="BrandEdit{{$brand->id}}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Редактировать</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body ">
                                                    <form method="post"  enctype="multipart/form-data"  action="{{route('admin.brand.update' , $brand->id)}}">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="input-group mb-2 col-lg-18">
                                                            <input type="text" class="form-control" name="name"  placeholder="Введите имя " value="{{$brand->name}}" aria-describedby="basic-addon1" required>
                                                        </div>
                                                        <div class="align-content-end input-group mb-2 col-lg-18 offset-lg-8  ">
                                                            <button type="submit" class="btn-warning">Изменить</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!--Brand Delete -->
                                    <form action="{{route('admin.brand.destroy', $brand->id)}}" method="POST"  class="d-inline-block">
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
                    {!! $brands->appends(['sort' => 'id'])->links() !!}
                </div>
            </div>
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
