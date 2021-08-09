<?php

namespace App\Http\Controllers\Backend;

use App\AdminUser;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\AdminUserRequest;
use App\Http\Requests\AdminUserUpdateRequest;


class AdminUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.admin_user.index');
    }


    public function datatable(){
        $data = AdminUser::query();
        return Datatables::of($data)
        ->editColumn('user_agent', function($each){
            
            if($each->user_agent){
                $agent = new Agent();
                $device = $agent->device();
                $browser = $agent->browser();
    
                return  'Device-'.$device.'<br> Browser-'.$browser;
            }

            return '---';
           

        })

        ->editColumn('updated_at', function($each){

            $date = $each->updated_at->format('D m Y');
            $time = $each->updated_at->format('h:ia');
            return $date.'<br>'.$time;

        })
        ->addColumn('action', function($each){

            $edit_btn = '<a href="'.route('admin.admin-users.edit',$each->id).'" class="btn btn-warning btn-sm mr-3"><i class="fa fa-edit" aria-hidden="true"></i>Edit</a>'; 
            $del_btn = '<form action="'.route('admin.admin-users.destroy',$each->id).'" method="POST" class="d-inline-block" id="delForm'.$each->id.'"> 
            <input type="hidden" name="_token" value="'.csrf_token().'">
            <input type="hidden" name="_method" value="DELETE">
            <button type="button" data-id="'.$each->id.'" class="btn btn-danger btn-sm del"><i class="fa fa-trash" aria-hidden="true"></i> Delete</button>
                        </form>';

            return $edit_btn.$del_btn;

            // <a href="'.route('admin.admin-users.destroy', $each->id).'" class="btn btn-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i>Delete</a>

        })
        ->rawColumns(['user_agent', 'action', 'updated_at'])
        ->make(true);
    }


    public function create()
    {
        return view('backend.admin_user.create');
    }

    public function store(AdminUserRequest $request)
    {
        $admin_user = new AdminUser();
        $admin_user->name = $request->name;
        $admin_user->email = $request->email;
        $admin_user->phone = $request->phone;
        $admin_user->password = Hash::make($request->password);
        
        if($admin_user->save()){

            return redirect()->route('admin.admin-users.index')->with('toast', ['icon' => 'success', 'title' => 'User Created Successfully!!!']);

        }
    }

    public function show(AdminUser $adminUser)
    {
        //
    }

    public function edit(AdminUser $adminUser)
    {
     return view('backend.admin_user.edit', compact('adminUser'));
    }

    public function update(AdminUserUpdateRequest $request, AdminUser $adminUser)
    {
        $adminUser->name = $request->name;
        $adminUser->email = $request->email;
        $adminUser->phone = $request->phone;
        $adminUser->password = $request->password ? Hash::make($request->password) : $adminUser->password;
        
        if($adminUser->save()){

            return redirect()->route('admin.admin-users.index')->with('toast', ['icon' => 'success', 'title' => 'User Updated Successfully!!!']);

        }
    }

    public function destroy($id)
    {   
        $adminUser = AdminUser::findOrFail($id);
        $adminUser->delete();
        return redirect()->route('admin.admin-users.index')->with('toast', ['icon' => 'success', 'title' => 'User Deleted Successfully!!!']);
    }
}
