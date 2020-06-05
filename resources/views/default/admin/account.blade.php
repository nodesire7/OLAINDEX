@php
    /* @var $accounts \App\Models\Account[]|\Illuminate\Pagination\Paginator*/
@endphp
@extends('default.layouts.main')
@section('title', '账号列表')
@section('content')
    <div class="card mb-3">
        <div class="card-header">账号列表</div>
        <div class="card-body table-responsive">
            <table class="table table-hover table-borderless">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">备注</th>
                    <th scope="col">类型</th>
                    <th scope="col">状态</th>
                    <th scope="col">刷新时间</th>
                    <th scope="col">操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($accounts as $account)
                    <tr>
                        <th scope="row">{{ $account->id }}</th>
                        <td>
                            <label>
                                <input type="text" class="remark" value="{{ $account->remark }}" data-id="{{ $account->id }}">
                            </label>
                        </td>
                        <td>{{ $account->accountType }}</td>
                        <td>{!! $account->status ? '<span style="color:green">正常</span>':'<span style="color:red">禁用</span>' !!}</td>
                        <td>{{ $account->updated_at }}</td>
                        <td>
                            <a class="btn btn-primary btn-sm"
                               href="{{ route('admin.account.config',['id' =>$account->id])  }}">设置</a>
                            <div class="btn-group" role="group">
                                <button id="actionAccount" type="button" class="btn btn-primary btn-sm dropdown-toggle"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">更多
                                </button>
                                <div class="dropdown-menu" aria-labelledby="actionAccount"
                                     data-id="{{ $account->id }}">
                                    <a class="dropdown-item view_account"
                                       href="javascript:void(0)" data-toggle="modal" data-target="#viewAccount">账号详情</a>
                                    <a class="dropdown-item view_drive"
                                       href="javascript:void(0)" data-toggle="modal" data-target="#viewDrive">网盘详情</a>
                                    <a class="dropdown-item text-danger delete_account"
                                       href="javascript:void(0)">删除</a>

                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $accounts->links()  }}
        </div>
    </div>
    <div class="modal fade" id="viewAccount" tabindex="-1" role="dialog" aria-labelledby="viewAccount"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">账号信息</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-control-label" for="id">id </label>
                        <input type="text" class="form-control" id="id" name="id"
                               value="" readonly>
                    </div>
                    <div class="form-group">
                        <label class="form-control-label" for="displayName">displayName </label>
                        <input type="text" class="form-control" id="displayName" name="displayName"
                               value="" readonly>
                    </div>
                    <div class="form-group">
                        <label class="form-control-label" for="userPrincipalName">userPrincipalName </label>
                        <input type="text" class="form-control" id="userPrincipalName" name="userPrincipalName"
                               value="" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="viewDrive" tabindex="-1" role="dialog" aria-labelledby="viewDrive"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">网盘信息</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-control-label" for="state">state </label>
                        <input type="text" class="form-control" id="state" name="state"
                               value="" readonly>
                    </div>
                    <div class="form-group">
                        <label class="form-control-label" for="total">total </label>
                        <input type="text" class="form-control" id="total" name="total"
                               value="" readonly>
                    </div>
                    <div class="form-group">
                        <label class="form-control-label" for="used">used </label>
                        <input type="text" class="form-control" id="used" name="used"
                               value="" readonly>
                    </div>
                    <div class="form-group">
                        <label class="form-control-label" for="remaining">remaining </label>
                        <input type="text" class="form-control" id="remaining" name="remaining"
                               value="" readonly>
                    </div>
                    <div class="form-group">
                        <label class="form-control-label" for="deleted">deleted </label>
                        <input type="text" class="form-control" id="deleted" name="deleted"
                               value="" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('js')
    @parent
    <script>
        function readablizeBytes(bytes) {
            if (!bytes) return 0
            let s = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB']
            let e = Math.floor(Math.log(bytes) / Math.log(1024))
            return (bytes / Math.pow(1024, Math.floor(e))).toFixed(2) + ' ' + s[e]
        }

        $(function() {
            $('.view_account').on('click', function(e) {
                let account_id = $(this).parent().attr('data-id')
                axios.get('/admin/account/' + account_id + '/drive')
                    .then(function(response) {
                        let data = response.data
                        $('#id').val(data.id)
                        $('#displayName').val(data.displayName)
                        $('#userPrincipalName').val(data.userPrincipalName)
                    })
                    .catch(function(error) {
                        console.log(error)
                    })
                    .then(function() {
                        // always executed
                    })
            })
            $('.view_drive').on('click', function(e) {
                let account_id = $(this).parent().attr('data-id')
                axios.get('/admin/account/' + account_id)
                    .then(function(response) {
                        let data = response.data
                        $('#total').val(readablizeBytes(data.total))
                        $('#deleted').val(readablizeBytes(data.deleted))
                        $('#used').val(readablizeBytes(data.used))
                        $('#remaining').val(readablizeBytes(data.remaining))
                        $('#state').val(data.state)
                    })
                    .catch(function(error) {
                        console.log(error)
                    })
                    .then(function() {
                        // always executed
                    })
            })
            $('.delete_account').on('click', function(e) {
                Swal.fire(
                    '删除？',
                    '对不起，暂时无法删除！',
                    'warning',
                )
            })
            $('.remark').on('change', function(e) {
                let account_id = $(this).attr('data-id')
                let remark = $(this).val()
                axios.post('/admin/account/' + account_id + '/remark', {
                    remark: remark,
                })
                    .then(function (response) {
                        console.log(response);
                        window.location.reload();
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            })
        })
    </script>
@stop
