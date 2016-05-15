@extends('layouts.app')

@section('css')
    <link href="{{ asset('/css/datatables.bootstrap.css') }}" rel="stylesheet">
@endsection

@section('content')

    <div class="container spark-screen">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Users
                        <button id="refresh-button" type="button" class="btn btn-xs btn-default tip pull-right" title="Refresh Table"><i class="fa fa-refresh"></i></button>
                        <button id="create-button" type="button" class="btn btn-xs btn-success tip pull-right" title="Create User"><i class="fa fa-plus"></i>Create</button>
                    </div>
                    <div class="panel-body">
                        <table id="user-table" class="table table-condensed">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="form-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title" id="modal-user-label">User Editor</h4>
                </div>
                <div class="modal-body">
                    <form id="user-form" name="user-form" class="form-horizontal" novalidate="">
                        <div id="form-errors"></div>
                        <div class="form-group">
                            <label for="input-name" class="col-sm-3 control-label">Name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="name" name="name" placeholder="Name" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input-name" class="col-sm-3 control-label">Email</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="email" name="email" placeholder="Email" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="select-role" class="col-sm-3 control-label">Password</label>
                            <div class="col-sm-9">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="select-role" class="col-sm-3 control-label">Password Confirmation</label>
                            <div class="col-sm-9">
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Password Confirmation" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="select-role" class="col-sm-3 control-label">Roles</label>
                            <div class="col-sm-9">
                                <select id="role-id" name="roles[]" class="form-control" multiple>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="save-button" value="add">Save</button>
                    <input type="hidden" id="user-id" name="id" value="">
                </div>
            </div>
        </div>
    </div>


    <div id="delete-modal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form id="delete-form" method="POST" action="" role="form" class="form-horizontal">
                <input name="_method" type="hidden" value="DELETE">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">User Delete Confirmation</h4>
                </div>
                <div class="modal-body">
                    Are you sure to Delete ?
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger" id="confirm-delete-botton" value="">Confirm</button>
                    <button type="button" data-dismiss="modal" class="btn default">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('plugins')
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
@endsection

@section('scripts')
    <script src="{{asset('script/user.js')}}"></script>
@endsection
