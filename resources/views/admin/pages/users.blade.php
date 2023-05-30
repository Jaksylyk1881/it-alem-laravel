@extends('admin.layouts.index')

@section('title', 'Пользователи')

@section('top_right_content')
    <div class="offset-lg-10 btn-lg">
        <a type="button"  data-bs-toggle="modal" data-bs-target="#AddNewUser">
            Создать
        </a>
    </div>

    <!--Add form new user-->
    <div class="modal fade" id="AddNewUser" tabindex="-3" aria-labelledby="AddNewUser" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Добавьте пользователя</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ">
                    <form method="post"  enctype="multipart/form-data"  action="{{route('admin.user.store')}}">
                        @csrf
                        <div class="  mb-2 col-lg-18 offset-lg-18">
                            <p style="padding: 5px"></p>
                            <label >Тип</label>
                            <select name="type" id="" style="color: #232c4d">
                                    <option value="1">client</option>
                                    <option value="2">company</option>
                            </select>
                        </div>
                        <div class="input-group mb-2 col-lg-18">
                            <input type="text" class="form-control" name="name"  placeholder="Введите имя " aria-describedby="basic-addon1" required>
                        </div>
                        <div class="input-group mb-2 col-lg-18">
                            <input type="text" class="form-control" name="phone"  placeholder="Введите телефон" aria-describedby="basic-addon1" required>
                        </div>
                        <div class="input-group mb-2 col-lg-18">
                            <input type="text" class="form-control" name="password"  placeholder="Новый пароль" aria-describedby="basic-addon1" required>
                        </div>
                        <div class="input-group mb-3 " >
                            <label style="font-style: italic;padding: 0px 4px">Загрузите аватар</label>
                            <input type="file" name="avatar" required>
                        </div>
                        <div class="form-group">
                            <hr>
                            <label for="description">Для компаний</label>
                        </div>
                        <div class="form-group">
                            <label for="description">Описание</label>
                            <textarea name="description" id="description" class="form-control"></textarea>
                        </div>
                        <div class="  mb-2 col-lg-18 offset-lg-18">
                            <p style="padding: 5px"></p>
                            <label >Адрес</label>
                            <div class="  mb-2 col-lg-18 offset-lg-18">
                                <div class="input-group mb-2 col-lg-18">
                                    <input type="text" class="form-control" name="address[email]"  placeholder="Электронная почта" aria-describedby="basic-addon1">
                                </div>
                                <div class="input-group mb-2 col-lg-18">
                                    <input type="text" class="form-control" name="address[postcode]"  placeholder="Почтовой индекс" aria-describedby="basic-addon1">
                                </div>
                                <div class="input-group mb-2 col-lg-18">
                                    <input type="text" class="form-control" name="address[street]"  placeholder="Улица, дом, квартира" aria-describedby="basic-addon1">
                                </div>
                                <div class="input-group mb-2 col-lg-18">
                                    <input type="text" class="form-control" name="address[home]"  placeholder="Подъезд, домофон, этаж" aria-describedby="basic-addon1">
                                </div>
                                <div class="  mb-2 col-lg-18 offset-lg-18">
                                    <p style="padding: 5px"></p>
                                    <label >Город</label>
                                    <select name="address[city_id]" id="" style="color: #232c4d">
                                        <option value="">Не выбрано</option>
                                        @foreach($cities as $v)
                                            <option value="{{$v->id}}">{{$v->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3 " >
                            <label style="font-style: italic;padding: 0px 4px">Загрузите изображение компаний</label>
                            <input type="file" name="images[]" multiple>
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
                        @foreach ($users as $user)
                            <tr>
                                <td>
                                    {{ $user->id}}
                                </td>
                                <td>
                                    {{$user->name}}
                                </td>
                                <td class="text-center">
                                    <!-- Button trigger User Edit -->
                                    <a href="#"  type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#UserEdit{{$user->id}}">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <!-- Form User Edit -->
                                    <div class="modal fade" id="UserEdit{{$user->id}}" tabindex="-3" aria-labelledby="UserEdit{{$user->id}}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Редактировать</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body ">
                                                    <form method="post"  enctype="multipart/form-data"  action="{{route('admin.user.update' , $user->id)}}">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="input-group mb-2 col-lg-18 offset-lg-18">
                                                            <p style="padding: 5px"></p>
                                                            <label >Тип</label>
                                                            <select name="type" id="" style="color: #232c4d">
                                                                <option value="1" @selected('client' == $user->type)>client</option>
                                                                <option value="2" @selected('company' == $user->type)>company</option>
                                                            </select>
                                                        </div>
                                                        <div class="input-group mb-2 col-lg-18">
                                                            <input type="text" class="form-control" name="name"  placeholder="Введите имя " value="{{$user->name}}" aria-describedby="basic-addon1" required>
                                                        </div>
                                                        <div class="input-group mb-2 col-lg-18">
                                                            <input type="text" class="form-control" name="phone"  placeholder="Введите телефон " value="{{$user->phone}}" aria-describedby="basic-addon1" required>
                                                        </div>
                                                        <div class="input-group mb-2 col-lg-18">
                                                            <input type="text" class="form-control" name="password"  placeholder="Введите пароль " value="" aria-describedby="basic-addon1">
                                                        </div>

                                                        <div class="input-group mb-3 " >
                                                            <label style="font-style: italic;padding: 0px 4px">Загрузите аватар</label>
                                                            <img src="{{$user->avatar}}" class="w-100 h-100 mb-4">
                                                            <input type="file" name="avatar">
                                                        </div>
                                                        <div class="form-group">
                                                            <hr>
                                                            <label for="description">Для компаний</label>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="description">Описание</label>
                                                            <textarea name="description" id="description" class="form-control">{{$user->description}}</textarea>
                                                        </div>
                                                        <div class="input-group mb-2 col-lg-18 offset-lg-18">
                                                            <p style="padding: 5px"></p>
                                                            <label >Адрес</label>
                                                            <select name="address_id" id="" style="color: #232c4d">
                                                                <option value="">Не выбрано</option>
                                                                @foreach($addresses as $v)
                                                                    <option value="{{$v->id}}" @selected($v->id == $user->address_id)>{{$v->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="input-group mb-2 col-lg-18">
                                                            @foreach($user->images()->get() as $image)
                                                                <div class="row">
                                                                    <div class="col-4">
                                                                        <a href="{{route('admin.user.destroy.image' , $image->id)}}" onclick="return confirm('Вы уверены что хотите удалить?')" class="btn btn-default btn-sm">
                                                                            <img src="{{$image->path}}" class="w-100 mb-4">
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        <div class="input-group mb-2 col-lg-18" >
                                                            <label style="font-style: italic;padding: 0px 4px">Загрузите изобрежение компаний</label>
                                                            <input type="file" name="images[]" multiple>
                                                        </div>
                                                        <div class="align-content-end input-group mb-2 col-lg-18 offset-lg-8  ">
                                                            <button type="submit" class="btn-warning">Изменить</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!--User Delete -->
                                    <form action="{{route('admin.user.destroy', $user->id)}}" method="POST"  class="d-inline-block">
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
                    {!! $users->appends(['sort' => 'id'])->links() !!}
                </div>
            </div>
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
