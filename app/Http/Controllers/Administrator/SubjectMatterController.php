<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\SubjectMatter;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Validator;

class SubjectMatterController extends Controller
{
    public function index()
    {
        return view('administrator.subject_matter');
    }
    /* Create */
    public function create(Request $request)
    {
        $this->validation($request->all());
        SubjectMatter::create($request->all());
        return response()->json($request->all(), 200);
    }

    /* Edit */
    public function edit($id)
    {
        /* I Know It's really bad, but I haven't found any other way */
        $subject      = SubjectMatter::findOrFail($id);

        $items      = collect($subject)
            ->merge([
                'teacher_name'    => $subject->teacher->name,
            ])
            ->merge([
                'course_name'      => $subject->course->name,
            ])
            ->merge([
                'classroom_name'    => $subject->classroom->name,
            ]);

        return response()->json($items, 200);
    }

    /* Update */
    public function update(Request $request)
    {
        $this->validation($request->all());
        SubjectMatter::findOrFail($request->id)->update($request->all());
        return response()->json('Success', 200);
    }

    /* Delete */
    public function delete($id)
    {
        SubjectMatter::findOrFail($id)->delete();
        return response()->json('Success', 200,);
    }
    public function course()
    {
        return response()->json(Course::get(['id', 'name']), 200);
    }

    /* Validation Request */
    public function validation($request, $id = 0)
    {
        return Validator::make($request, [
            'course_id'    => 'required',
            'teacher_id'   => 'required',
            'classroom_id' => 'required',
            'name'         => 'required',
            'information'  => 'required',
            'youtube'      => 'required',
            'link'         => 'required',
            'start'        => 'required',
            'status'       => 'required',
        ])->validate();
    }

    public function dataTable()
    {

        $data = SubjectMatter::query();
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('course', function (SubjectMatter $model) { // get name from table users
                return $model->course->name;
            })

            ->addColumn('teacher', function (SubjectMatter $model) { // get name from table users
                return $model->teacher->name;
            })

            ->addColumn('classroom', function (SubjectMatter $model) { // get name from table users
                return $model->classroom->name;
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
            ->addColumn('start', function ($row){
                return \Carbon\Carbon::parse($row->start)->format("d F Y");
            })

            ->addColumn('end', function ($row) { //button edit and delete
                if ($row->end == null) {
                    $end = 'No end of materi';
                } else {
                    $end = \Carbon\Carbon::parse($row->end)->format("d F Y");
                }
                return $end;
            })
            ->rawColumns(['action', 'course', 'teacher', 'classroom', 'status', 'start', 'end'])
            ->make(true);
    }
}
