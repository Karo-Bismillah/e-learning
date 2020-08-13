<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Validator;

class CourseController extends Controller
{
    public function index()
    {
        return view('administrator.course');
    }

    /* Create */
    public function create(Request $request)
    {
        $this->validation($request->all());
        Course::create($request->all());
        return response()->json('Success', 200);
    }

    /* Edit */
    public function edit($id)
    {
        /* I Know It's really bad, but I haven't found any other way */
        $course      = Course::findOrFail($id);
        $items      = collect($course)->merge([
            'teacher_name'    => $course->teacher->name,
        ]);
        return response()->json($items, 200);
    }

    /* Update */
    public function update(Request $request)
    {
        $this->validation($request->all());
        Course::findOrFail($request->id)->update($request->all());
        return response()->json('Success', 200);
    }

    /* Delete */
    public function delete($id)
    {
        Course::findOrFail($id)->delete();
        return response()->json('Success', 200);
    }
    /* Validation Request */
    public function validation($request, $id = 0)
    {
        return Validator::make($request, [
            'name'          => 'required',
            'teacher_id'    => 'required|numeric',
        ])->validate();
    }

    public function dataTable()
    {
        $data = Course::query();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('teacher', function (Course $course) { //get Teacher from table users active or inactive
                return $course->teacher->name;
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
            ->rawColumns(['action', 'status', 'teacher'])
            ->make(true);
    }
}
