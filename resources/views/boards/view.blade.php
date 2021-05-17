@extends('layout.main')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Board view</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{route('boards.all')}}">Boards</a></li>
                        <li class="breadcrumb-item active">Board</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{$board->name}}</h3>
            </div>

            <div class="card-body">
                <select class="custom-select rounded-0" id="changeBoard">
                    @foreach($boards as $selectBoard)
                        <option @if ($selectBoard->id === $board->id) selected="selected" @endif value="{{$selectBoard->id}}">{{$selectBoard->name}}</option>
                    @endforeach
                </select>
            </div>

            <table class="table table-bordered">
                    <thead>
                        <tr>
                            <!-- <th style="width: 10px">#</th> -->
                            <th>Name</th>
                            <th>Description</th>
                            <th>Assignment</th>
                            <th style="width: 100px">Status</th>
                            <th style="width: 100px">Date of creation</th>
                            <th style="width: 40px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tasks as $task)
                            <tr>
                                <td>{{$task->name}}</td>
                                <td>{{$task->description}}</td>
                                <td>{{$task->assignment}}</td>
                                <td>{{ $task->status === \App\Models\Task::STATUS_IN_PROGRESS ? 'In progress' : 
                                    ($task->status === \App\Models\Task::STATUS_CREATED ? 'Created' : "Done")}}</td>
                                <td>{{$task->created_at}}</td>
                            
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-xs btn-primary"
                                                type="button"
                                                data-board="{{json_encode($task)}}"
                                                data-toggle="modal"
                                                data-target="#boardEditModal">
                                            <i class="fas fa-edit"></i></button>
                                        <button class="btn btn-xs btn-danger"
                                                type="button"
                                                data-board="{{json_encode($task)}}"
                                                data-toggle="modal"
                                                data-target="#boardDeleteModal">
                                            <i class="fas fa-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

        </div>
        <!-- /.card -->


    </section>
    <!-- /.content -->
@endsection