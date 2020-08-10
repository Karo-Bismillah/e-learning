<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Student;
use App\User;
use Illuminate\Http\Request;
use Validator;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    public function index()
    {
        return view('administrator.student');
    }

    public function create(Request $request)
    {
        $this->validation($request->all());
        User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'password'          => Hash::make($request->password),
            'remember_token'    => Str::random(60),
            'role'              => 'student',
            'status'            => $request->status,
        ]);

        /* Find user_id using email */
        $user_id = User::where('email', $request->email)->first();

        Student::create([
            'name'          => $request->name,
            'user_id'       => $user_id->id,
            'classroom_id'  => $request->classroom_id,
            'gender'        => $request->gender,
            'dob'           => $request->dob,
            'address'       => $request->address,
        ]);
        return response()->json('Success', 200);
    }

    /* Edit */
    public function edit($id)
    {
        /* It's really bad, but I haven't found any other way */
        $student = Student::findOrFail($id);
        $user = User::findOrFail($student->user_id);
        $class = Classroom::findOrFail($student->classroom_id);
        $items = collect($student)->merge([
            'status'    => $user->status,
            'email'     => $user->email,
        ])->merge(['classroom_name' => $class->name]);

        return response()->json($items, 200);
    }
    /* Update */
    public function update(Request $request)
    {
        $student = Student::findOrFail($request->id);

        $this->validation($request->all(), $student->user_id); // Validate request
        if ($request->password == 'no changes') { //Check if password no changes
            User::findOrFail($student->user_id)->update([
                'name'              => $request->name,
                'email'             => $request->email,
                'status'            => $request->status,
            ]);

            Student::findOrFail($student->id)->update([
                'name'          => $request->name,
                'classroom_id'  => $request->classroom_id,
                'gender'        => $request->gender,
                'dob'           => $request->dob,
                'address'       => $request->address,
            ]);
        } else {
            User::findOrFail($student->user_id)->update([
                'name'              => $request->name,
                'email'             => $request->email,
                'password'          => Hash::make($request->password),
                'status'            => $request->status,
            ]);

            Student::findOrFail($student->id)->update([
                'name'          => $request->name,
                'classroom_id'  => $request->classroom_id,
                'gender'        => $request->gender,
                'dob'           => $request->dob,
                'address'       => $request->address,
            ]);
        }
        return response()->json('Success', 200);
    }

    /* Delete */
    public function delete($id) // Delete function
    {
        $student = Student::findOrFail($id);
        User::findOrFail($student->user_id)->delete();
        $student->delete();
        return response()->json('Success', 200);
    }

    /* Validation Request */
    public function validation($request, $id=0)
    {
        return Validator::make($request, [
            'name'          => 'required',
            'gender'        => 'required',
            'dob'           => 'required|date',
            'address'       => 'required',
            'email'         => 'required|email|unique:users,email,'. $id, // give condition for email if update
            'password'      => 'required|confirmed',
        ])->validate();
    }

    public function classroom()
    {
        return response()->json(Classroom::get(['id', 'name']), 200);
    }

    /* DataTable */
    public function dataTable()
    {
        $data = Student::query(); //harus query supaya serversidenya jalan
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('classroom', function (Student $student) { // get class_name from table class
                return $student->classroom->name;
            })
            ->addColumn('email', function (Student $student) { // get class_name from table class
                return $student->user->email;
            })
            ->addColumn('status', function (Student $model) { //get status Teacher from table users active or inactive
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
            ->rawColumns(['action', 'classroom', 'status', 'email'])
            ->make(true);
    }
}
