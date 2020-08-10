<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\User;
use Validator;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TeacherController extends Controller
{
    public function __construct()
    {
        # code...
    }

    public function index()
    {
        # code...
        return view('administrator.teacher', [
            'teachers' => Teacher::get(),
        ]);
    }

    public function create(Request $request)
    {
        $this->validation($request->all());
        User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'remember_token' => Str::random(60),
            'role'          => 'teacher',
            'status'        => $request->status,
        ]);

        /* Find user_id using email */
        $user_id = User::where('email', $request->email)->first();

        Teacher::create([
            'name'          => $request->name,
            'user_id'       => $user_id->id,
            'gender'        => $request->gender,
            'dob'           => $request->dob,
            'phone'         => $request->phone,
            'address'       => $request->address,
        ]);
        return response()->json('Success', 200);
    }

    public function edit($id)
    {
        $teacher = Teacher::findOrFail($id);
        $user = User::findOrFail($teacher->id)->first();

        $items = collect($teacher)->merge([
            'status'    => $user->status,
            'email'     => $user->email,
        ]);

        return response()->json($items, 200);
    }

    public function update(Request $request)
    {

        $this->validation($request->all()); //Validate request

        if ($request->password == 'no changes') { //Check if password no changes

            User::findOrFail($request->id)->update([
                'name'          => $request->name,
                'email'         => $request->email,
                'status'        => $request->status,
            ]);

            /* Find user_id using email */
            $user_id = User::where('email', $request->email)->first();

            Teacher::findOrFail($user_id->id)->update([
                'name'          => $request->name,
                'gender'        => $request->gender,
                'dob'           => $request->dob,
                'phone'         => $request->phone,
                'address'       => $request->address,
            ]);

            return response()->json($user_id, 200);
        } else {
            /* If password changed this function running */
            User::findOrFail($request->id)->update([
                'name'          => $request->name,
                'email'         => $request->email,
                'password'      => Hash::make($request->password),
                'status'        => $request->status,
            ]);

            /* Find user_id using email */
            $user_id = User::where('email', $request->email)->first();

            Teacher::findOrFail($user_id)->update([
                'name'          => $request->name,
                'gender'        => $request->gender,
                'dob'           => $request->dob,
                'phone'         => $request->phone,
                'address'       => $request->address,
            ]);
        }

        return response()->json('Success', 200);
    }

    public function delete($id) // Delete function
    {
        User::findOrFail($id)->delete();
        Teacher::where('user_id', $id)->delete();
        return response()->json('Success', 200);
    }

    /* Validation for request */
    public function validation($request)
    {
        return Validator::make($request,  [
            'name'          => 'required',//validation must be entirely alphabetic characters
            'gender'        => 'required',
            'dob'           => 'required|date',
            'phone'         => 'required|numeric',//validation must be entirely numeric
            'address'       => 'required',
            'email'         => 'sometimes|required|email|unique:users', // give condition for email if update
            'password'      => 'required|confirmed',
            'status'        => 'required|boolean',
        ])->validate();
    }

    public function dataTable()
    {
        $data = Teacher::query(); //harus query supaya serversidenya jalan
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('email', function (Teacher $model) { // get email from table users
                return $model->user->email;
            })
            ->addColumn('status', function (Teacher $model) { //get status Teacher from table users active or inactive
                if ($model->user->status == 0) {
                    return '<span class="badge mr-3 badge-pill badge-danger">InActive</span>';
                } else {
                    return '<span class="badge mr-3 badge-pill badge-success">Active</span>';
                }
            })
            ->addColumn('action', function ($row) { //button edit and delete
                $btn = '<a href="javascript:void(0)"
                 data-id="' . $row->id . '" data-original-title="Edit"
                 id="editBtn"><i class="fa fa-edit text-primary"></i></a> |';

                $btn = $btn . ' <a href="javascript:void(0)"
                data-id="' . $row->id . '" data-original-title="Delete"
                id="deleteBtn"><i class="fa fa-trash text-danger"></i></a>';

                return $btn;
            })
            ->rawColumns(['action', 'email', 'status'])
            ->make(true);
    }
}
