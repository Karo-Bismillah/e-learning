<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Validator;

class ClassroomController extends Controller
{
    public function index()
    {
        return view('administrator.classroom');
    }

    /* Create */
    public function create(Request $request)
    {
        $this->validation($request->all()); //Validation data first
        Classroom::create($request->all()); //Create to database
        return response()->json('Success', 200); //return success create
    }

    /* Edit */
    public function edit($id)
    {
        /* I Know It's really bad, but I haven't found any other way */
        $class      = Classroom::findOrFail($id);
        $teacher    = Teacher::findOrFail($class->teacher_id);
        $items      = collect($class)->merge([
            'teacher_name'    => $teacher->name,
        ]);
        return response()->json($items, 200);
    }

    /* Update */
    public function update(Request $request)
    {
        $this->validation($request->all(), $request->id);
        Classroom::findOrFail($request->id)->update($request->all());
        return response()->json($request->all(), 200);
    }

    /* Teacher json for dropdown */
    public function teacher()
    {
        return response()->json(Teacher::get(['id', 'name']), 200);
    }

    /* Validation for request */
    public function validation($request, $id=0)
    {
        return Validator::make($request,  [
            'name'          => 'required', //validation must be entirely alphabetic characters
            'teacher_id'    => 'required|numeric',
            'quota'         => 'required|numeric',
            'token'         => 'required|email|unique:classrooms,token,'. $id, //validation must be entirely numeric
            'status'        => 'required',
        ])->validate();
    }

    /* Delete */
    public function delete($id)
    {
        Classroom::findOrFail($id)->delete();
        return response()->json('Success', 200);
    }
    /* DataTable */
    public function dataTable()
    {
        $data = Classroom::query(); //harus query supaya serversidenya jalan
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('teacher', function (Classroom $classroom) { // get class_name from table class
                return $classroom->teacher->name;
            })

            ->addColumn('status', function ($row) { //get status Teacher from table users active or inactive
                if ($row->status == 0) {
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
