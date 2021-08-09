<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\UUIDGenerate;
use App\User;
use Exception;
use App\Wallet;
use Jenssegers\Agent\Agent;
use Yajra\Datatables\Datatables;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserUpdateRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.user.index');
    }


    public function datatable()
    {
        $data = User::query();
        return Datatables::of($data)
            ->editColumn('user_agent', function ($each) {

                if ($each->user_agent) {
                    $agent = new Agent();
                    $device = $agent->device();
                    $browser = $agent->browser();

                    return  'Device-' . $device . '<br> Browser-' . $browser;
                }

                return '---';
            })

            ->editColumn('updated_at', function ($each) {

                $date = $each->updated_at->format('D m Y');
                $time = $each->updated_at->format('h:ia');
                return $date . '<br>' . $time;
            })
            ->addColumn('action', function ($each) {

                $edit_btn = '<a href="' . route('admin.users.edit', $each->id) . '" class="btn btn-warning btn-sm mr-3"><i class="fa fa-edit" aria-hidden="true"></i>Edit</a>';
                $del_btn = '<form action="' . route('admin.users.destroy', $each->id) . '" method="POST" class="d-inline-block" id="delForm' . $each->id . '"> 
            <input type="hidden" name="_token" value="' . csrf_token() . '">
            <input type="hidden" name="_method" value="DELETE">
            <button type="button" data-id="' . $each->id . '" class="btn btn-danger btn-sm del"><i class="fa fa-trash" aria-hidden="true"></i> Delete</button>
                        </form>';

                return $edit_btn . $del_btn;

                // <a href="'.route('admin.admin-users.destroy', $each->id).'" class="btn btn-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i>Delete</a>

            })
            ->rawColumns(['user_agent', 'action', 'updated_at'])
            ->make(true);
    }


    public function create()
    {
        return view('backend.user.create');
    }

    public function store(UserRequest $request)
    {
        // Using db transcation manual and try catch (Not to add user in database if something is wrong in wallet)
        DB::beginTransaction();

        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            $user->save();

            // First array = check condition /if yes = sec array add to db including first array
            Wallet::firstOrCreate(
                [
                    'user_id' => $user->id
                ],
                [
                    'account_number' => UUIDGenerate::accountNumber(),
                    'amount' => 0
                ]
            );

            DB::commit();

            return redirect()->route('admin.users.index')->with('toast', ['icon' => 'success', 'title' => 'User Created Successfully!!!']);
        } catch (Exception $e) {

            DB::rollBack();
            return redirect()->back()->withErrors(['fail' => 'Something wrong'])->withInput();
            // return redirect()->back()->with('toast', ['icon' => 'fail', 'title' => 'Sth Wrong']);
        }
    }

    public function show()
    {
        //
    }

    public function edit($id)
    {
        $user = User::find($id);
        return view('backend.user.edit', compact('user'));
    }


    public function update(UserUpdateRequest $request, $id)
    {

        DB::beginTransaction();
        try {
            $user = User::find($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = $request->password ? Hash::make($request->password) : $user->password;
            $user->update();

            Wallet::firstOrCreate(
                [
                    'user_id' => $user->id
                ],
                [
                    'account_number' => UUIDGenerate::accountNumber(),
                    'amount' => 0
                ]
            );
            DB::commit();
            return redirect()->route('admin.users.index')->with('toast', ['icon' => 'success', 'title' => 'User Updated Successfully!!!']);
        } catch (Exception $e) {

            DB::rollBack();
            return redirect()->back()->withErrors(['fail' => 'Something wrong' . $e->getMessage()])->withInput();
        }
    }


    

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.users.index')->with('toast', ['icon' => 'success', 'title' => 'User Deleted Successfully!!!']);
    }
}
