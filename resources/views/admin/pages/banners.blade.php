@extends('admin.layouts.index')

@section('title', 'Баннеры')

@section('top_right_content')
    <div class="offset-lg-10 btn-lg">
        <a type="button"  data-bs-toggle="modal" data-bs-target="#AddNewBanner">
            Создать
        </a>
    </div>

    <!--Add form new banner-->
    <div class="modal fade" id="AddNewBanner" tabindex="-3" aria-labelledby="AddNewBanner" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Добавьте пользаветеля</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ">
                    <form method="post"  enctype="multipart/form-data"  action="{{route('admin.banner.store')}}">
                        @csrf
                        <div class="input-group mb-3 " >
                            <label style="font-style: italic;padding: 0px 4px">Загрузите изображение</label>
                            <input type="file" name="image" required>
                        </div>
                        <div class="input-group mb-3 " >
                            <label style="font-style: italic;padding: 0px 4px">Начало</label>
                            <input type="date" name="start_date" required>
                        </div>
                        <div class="input-group mb-3 " >
                            <label style="font-style: italic;padding: 0px 4px">Конец</label>
                            <input type="date" name="end_date" required>
                        </div>
                        <div class="  mb-2 col-lg-18 offset-lg-18">
                            <p style="padding: 5px"></p>
                            <label >Категория</label>
                            <select name="category_id" id="" style="color: #232c4d">
                                @foreach($categories as $v)
                                    <option value="{{$v->id}}">{{$v->name . "[". __($v->type)."]"}} </option>
                                @endforeach
                            </select>
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
                                Изображение
                            </th>
                            <th style="width: 40%">
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($banners as $banner)
                            <tr>
                                <td>
                                    {{ $banner->id}}
                                </td>
                                <td>
                                    <img src="{{$banner->image}}">
                                </td>
                                <td class="text-center">
                                    <!-- Button trigger Banner Edit -->
                                    <a href="#"  type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#BannerEdit{{$banner->id}}">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <!-- Form Banner Edit -->
                                    <div class="modal fade" id="BannerEdit{{$banner->id}}" tabindex="-3" aria-labelledby="BannerEdit{{$banner->id}}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Редактировать</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body ">
                                                    <form method="post"  enctype="multipart/form-data"  action="{{route('admin.banner.update' , $banner->id)}}">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="input-group mb-2 col-lg-18">
                                                            <img src="{{$banner->image}}" class="w-100 mb-4">
                                                        </div>
                                                        <div class="input-group mb-3 " >
                                                            <label style="font-style: italic;padding: 0px 4px">Загрузите новое изображение</label>
                                                            <input type="file" name="image">
                                                        </div>
                                                        <div class="input-group mb-3 " >
                                                            <label style="font-style: italic;padding: 0px 4px">Начало</label>
                                                            <input type="date" name="start_date" value="{{$banner->start_date}}" required>
                                                        </div>
                                                        <div class="input-group mb-3 " >
                                                            <label style="font-style: italic;padding: 0px 4px">Конец</label>
                                                            <input type="date" name="end_date" value="{{$banner->end_date}}" required>
                                                        </div>
                                                        <div class="input-group mb-2 col-lg-18 offset-lg-18">
                                                            <p style="padding: 5px"></p>
                                                            <label >Подкатегория</label>
                                                            <select name="category_id" id="" style="color: #232c4d" required>
                                                                @foreach($categories as $v)
                                                                    <option value="{{$v->id}}" @selected($v->id == $banner->category_id)>{{$v->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="align-content-end input-group mb-2 col-lg-18 offset-lg-8  ">
                                                            <button type="submit" class="btn-warning">Изменить</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!--Banner Delete -->
                                    <form action="{{route('admin.banner.destroy', $banner->id)}}" method="POST"  class="d-inline-block">
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
                    {!! $banners->appends(['sort' => 'id'])->links() !!}
                </div>
            </div>
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
