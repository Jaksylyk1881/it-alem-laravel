@extends('admin.layouts.index')

@section('title', 'Категорий')

@section('top_right_content')
    <div class="offset-lg-10 btn-lg">
        <a type="button"  data-bs-toggle="modal" data-bs-target="#AddNewCategory">
            Создать
        </a>
    </div>

    <!--Add form new category-->
    <div class="modal fade" id="AddNewCategory" tabindex="-3" aria-labelledby="AddNewCategory" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Добавьте категорию</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ">
                    <form method="post"  enctype="multipart/form-data"  action="{{route('admin.category.store')}}">
                        @csrf
                        <div class="input-group mb-2 col-lg-18">
                            <input type="text" class="form-control" name="name"  placeholder="Введите имя " aria-describedby="basic-addon1">
                        </div>
                        <div class="input-group mb-3 " >
                            <label style="font-style: italic;padding: 0px 4px">Загрузите изображение</label>
                            <input type="file" name="image">
                        </div>
                        <div class="  mb-2 col-lg-18 offset-lg-18">
                            <p style="padding: 5px"></p>
                            <label >Подкатегория</label>
                            <select name="parent_id" id="" style="color: #232c4d">
                                <option value="">Не выбрано</option>
                                @foreach($categories as $v)
                                    <option value="{{$v->id}}">{{$v->name}}</option>
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
                                Название
                            </th>
                            <th style="width: 40%">
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td>
                                    {{ $category->id}}
                                </td>
                                <td>
                                    {{$category->name}}
                                </td>
                                <td class="text-center">
                                    <!-- Button trigger Category Edit -->
                                    <a href="#"  type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#CategoryEdit{{$category->id}}">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <!-- Form Category Edit -->
                                    <div class="modal fade" id="CategoryEdit{{$category->id}}" tabindex="-3" aria-labelledby="CategoryEdit{{$category->id}}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Редактировать</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body ">
                                                    <form method="post"  enctype="multipart/form-data"  action="{{route('admin.category.update' , $category->id)}}">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="input-group mb-2 col-lg-18">
                                                            <input type="text" class="form-control" name="name"  placeholder="Введите имя " value="{{$category->name}}" aria-describedby="basic-addon1">
                                                        </div>
                                                        <div class="input-group mb-2 col-lg-18">
                                                            <img src="{{$category->image}}" class="w-100 mb-4">
                                                        </div>
                                                        <div class="input-group mb-3 " >
                                                            <label style="font-style: italic;padding: 0px 4px">Загрузите новое изобрежение</label>
                                                            <input type="file" name="image">
                                                        </div>
                                                        <div class="input-group mb-2 col-lg-18 offset-lg-18">
                                                            <p style="padding: 5px"></p>
                                                            <label >Подкатегория</label>
                                                            <select name="parent_id" id="" style="color: #232c4d">
                                                                <option value="">Не выбрано</option>
                                                                @foreach($categories as $v)
                                                                    <option value="{{$v->id}}" @selected($v->id == $category->parent_id)>{{$v->name}}</option>
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

                                    <!--Category Delete -->
                                    <form action="{{route('admin.category.destroy', $category->id)}}" method="POST"  class="d-inline-block">
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
                    {!! $categories->appends(['sort' => 'id'])->links() !!}
                </div>
            </div>
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
