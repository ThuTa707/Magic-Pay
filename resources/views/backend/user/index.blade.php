@extends('backend.layouts.app')

@section('title', 'User Management')
@section('users-active', 'mm-active')
@section('content')

    <div class="app-page-title">
        <div class="page-title-wrapper">

            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-users icon-gradient bg-mean-fruit">
                    </i>
                </div>
                <div>Users
                </div>


            </div>

            <a href="{{ route('admin.users.create') }}" class="btn btn-primary mt-2 ml-3"><i
                    class="fa fa-plus-circle" aria-hidden="true"></i> Create User</a>

        </div>





        <div class="card mt-2">
            <div class="card-body">

                <table id="tableTest" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th style="width: 100px">Name</th>
                            <th style="width: 130px">Email</th>
                            <th>Phone</th>
                            <th>User Agent</th>
                            <th>Action</th>
                            <th>Updated At</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>

            </div>

        </div>

    </div>


@endsection


@section('foot')
    <script>
        $(function() {
            $('#tableTest').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "/admin/datatable/users/", // Pls write carefully your route 
                // "ajax": "{!! route('admin.datatable') !!}" Route name nk ll yayy lo ya
                columns: [{
                        data: 'id',
                        name: 'id',


                    },
                    {
                        data: 'name',
                        name: 'name',

                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'phone',
                        name: 'phone'

                    },
                    {
                        data: 'user_agent',
                        name: 'user_agent',
                        sortable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        sortable: false,
                        searchable: false


                    },
                    {
                        data: 'updated_at',
                        name: 'updated_at',
                        searchable: false


                    }

                ]
                ,
                order: [
                    [6, "desc"]
                ]


                // Using  Columnn Definition
                // ,
                // columnDefs: [{
                //     "targets": '1',
                //     "sortable": false
                // }]


            });
        });

        $(document).on('click', '.del', function() {

            // Taking id from data-id
            let id = $(this).data('id');

            Swal.fire({
                title: 'Are you sure to delete ?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirm',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                    )

                        $('#delForm' + id).submit()

                }
            })

        })
    </script>

@endsection
