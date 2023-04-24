@extends('admin.layouts.index')

@section('title', 'Товары/услуги')

@section('top_right_content')
    <div class="offset-lg-10 btn-lg">
        <a type="button"  data-bs-toggle="modal" data-bs-target="#AddNewProduct">
            Создать
        </a>
    </div>

    <!--Add form new product-->
    <div class="modal fade" id="AddNewProduct" tabindex="-3" aria-labelledby="AddNewProduct" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Добавьте пользаветеля</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ">
                    <form method="post"  enctype="multipart/form-data"  action="{{route('admin.product.store')}}">
                        @csrf
                        <div class="  mb-2 col-lg-18 offset-lg-18">
                            <p style="padding: 5px"></p>
                            <label >Пользователь</label>
                            <select name="user_id" id="" style="color: #232c4d">
                                @foreach($users as $v)
                                    <option value="{{$v->id}}">{{$v->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="  mb-2 col-lg-18 offset-lg-18">
                            <p style="padding: 5px"></p>
                            <label >Бренд</label>
                            <select name="brand_id" id="" style="color: #232c4d">
                                @foreach($brands as $v)
                                    <option value="{{$v->id}}">{{$v->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="  mb-2 col-lg-18 offset-lg-18">
                            <p style="padding: 5px"></p>
                            <label >Категория</label>
                            <select name="category_id" id="" style="color: #232c4d">
                                @foreach($categories as $v)
                                    <option value="{{$v->id}}">{{$v->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input-group mb-2 col-lg-18">
                            <input type="text" class="form-control" name="name"  placeholder="Введите имя " aria-describedby="basic-addon1">
                        </div>
                        <div class="input-group mb-2 col-lg-18">
                            <input type="text" class="form-control" name="price"  placeholder="Введите цену" aria-describedby="basic-addon1">
                        </div>
                        <div class="input-group mb-2 col-lg-18">
                            <input type="text" class="form-control" name="count"  placeholder="Новый количество" aria-describedby="basic-addon1">
                        </div>
                        <div class="form-group">
                            <label for="characteristics">Характеристики</label>
                            <textarea name="characteristics" id="characteristics" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="description">Описание</label>
                            <textarea name="description" id="description" class="form-control"></textarea>
                        </div>
                        <div class="input-group mb-3 " >
                            <label style="font-style: italic;padding: 0px 4px">Загрузите изобрежение</label>
                            <input type="file" name="images[]" multiple>
                        </div>

                        <div class="  mb-2 col-lg-18 offset-lg-18">
                            <p style="padding: 5px"></p>
                            <label >Подарок</label>
                            <select class="select" multiple name="gifts[]" id="">
                                <option value="">Не выбрано</option>
                                @foreach($products as $v)
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
                        @foreach ($products as $product)
                            <tr>
                                <td>
                                    {{ $product->id}}
                                </td>
                                <td>
                                    {{$product->name}}
                                </td>
                                <td class="text-center">
                                    <!-- Button trigger Product Edit -->
                                    <a href="#"  type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#ProductEdit{{$product->id}}">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <!-- Form Product Edit -->
                                    <div class="modal fade" id="ProductEdit{{$product->id}}" tabindex="-3" aria-labelledby="ProductEdit{{$product->id}}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Редактировать</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body ">
                                                    <form method="post"  enctype="multipart/form-data"  action="{{route('admin.product.update' , $product->id)}}">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="input-group mb-2 col-lg-18 offset-lg-18">
                                                            <p style="padding: 5px"></p>
                                                            <label >Бренд</label>
                                                            <select name="brand_id" id="" style="color: #232c4d">
                                                                @foreach($brands as $v)
                                                                    <option value="{{$v->id}}" @selected($v->id == $product->brand_id)>{{$v->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="input-group mb-2 col-lg-18 offset-lg-18">
                                                            <p style="padding: 5px"></p>
                                                            <label >Пользователь</label>
                                                            <select name="user_id" id="" style="color: #232c4d">
                                                                @foreach($users as $v)
                                                                    <option value="{{$v->id}}" @selected($v->id == $product->user_id)>{{$v->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="input-group mb-2 col-lg-18 offset-lg-18">
                                                            <p style="padding: 5px"></p>
                                                            <label >Категория</label>
                                                            <select name="category_id" id="" style="color: #232c4d">
                                                                @foreach($categories as $v)
                                                                    <option value="{{$v->id}}" @selected($v->id == $product->category_id)>{{$v->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="input-group mb-2 col-lg-18">
                                                            <input type="text" class="form-control" name="name"  placeholder="Введите имя " value="{{$product->name}}" aria-describedby="basic-addon1">
                                                        </div>
                                                        <div class="input-group mb-2 col-lg-18">
                                                            <input type="text" class="form-control" name="price"  placeholder="Введите цену " value="{{$product->price}}" aria-describedby="basic-addon1">
                                                        </div>
                                                        <div class="input-group mb-2 col-lg-18">
                                                            <input type="text" class="form-control" name="count"  placeholder="Введите количество " value="{{$product->count}}" aria-describedby="basic-addon1">
                                                        </div>


                                                        <div class="input-group mb-3 " >
                                                            <label for="characteristics">Характеристики</label>
                                                            <textarea name="characteristics" id="characteristics" class="form-control">{{$product->characteristics}}</textarea>
                                                        </div>
                                                        <div class="input-group mb-3 " >
                                                            <label for="description">Описание</label>
                                                            <textarea name="description" id="description" class="form-control">{{$product->description}}</textarea>
                                                        </div>

                                                        <div class="input-group mb-2 col-lg-18">
                                                            @foreach($product->images()->get() as $image)
                                                                <div class="row">
                                                                    <div class="col-4">
                                                                        <a href="{{route('admin.product.destroy.image' , $image->id)}}" onclick="return confirm('Вы уверены что хотите удалить?')" class="btn btn-default btn-sm">
                                                                            <img src="{{$image->path}}" class="w-100 mb-4">
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>

                                                        <div class="input-group mb-3 " >
                                                            <p style="padding: 5px"></p>
                                                            <label >Подарок</label>
                                                            <select class="select" multiple name="gifts[]" id="">
                                                                <option value="">Не выбрано</option>
                                                                @foreach($products as $v)
                                                                    <option value="{{$v->id}}" @selected(in_array($v->id, $product->gifts()->pluck('gift_product_id')->toArray()))>{{$v->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="input-group mb-3 " >
                                                            <label style="font-style: italic;padding: 0px 4px">Загрузите новое изобрежение</label>
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

                                    <!--Product Delete -->
                                    <form action="{{route('admin.product.destroy', $product->id)}}" method="POST"  class="d-inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm delete-btn" href="#">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    <a href="{{route('admin.product.review.index', $product->id)}}"  type="button" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-comment"></i>
                                        {{$product->reviews->count()}}
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
                    {!! $products->appends(['sort' => 'id'])->links() !!}
                </div>
            </div>
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
