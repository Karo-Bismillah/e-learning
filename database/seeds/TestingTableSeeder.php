<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TestingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* Table users */
        \App\User::create([
            'name'          => 'Im Administrator',
            'email'         => 'admin' . '@local.com',
            'password'      => Hash::make('bismillah'),
            'role'          => 'administrator',
            'status'        => 1,
        ]);

        \App\User::create([
            'name'          => 'Im Teacher 1',
            'email'         => 'imteacher1' . '@local.com',
            'password'      => Hash::make('bismillah'),
            'role'          => 'teacher',
            'status'        => 1,
        ]);

        \App\User::create([
            'name'          => 'Im Student 1',
            'email'         => 'imstudent1' . '@local.com',
            'password'      => Hash::make('bismillah'),
            'role'          => 'student',
            'status'        => 1,
        ]);

        /* Table courses */
        \App\Models\Course::create([
            'teacher_id'    => 1,
            'name'          => 'Course 1',
            'status'        => 1,
        ]);

        /* Table teachers */
        \App\Models\Teacher::create([
            'user_id'       => 2,
            'name'          => 'Im Teacher 1',
            'gender'        => 'Female',
            'dob'           => Carbon::parse('1991-01-01'),
            'phone'         => '08123456789',
            'address'       => 'Random Address',
        ]);

        /* Table student */
        \App\Models\Student::create([
            'user_id'           => 2,
            'classroom_id'      => 1,
            'name'              => 'Im Student 1',
            'gender'            => 'Male',
            'dob'               => Carbon::parse('1991-01-01'),
            'address'           => 'Random Address',
        ]);

        /* Table classrooms */
        \App\Models\Classroom::create([
            'name'           => 'Classroom 1',
            'teacher_id'     => 1,
            'quota'          => 50,
            'token'          => 'TESTING',
            'status'         => 1,
        ]);

        /* Table subcjet_matter */
        \App\Models\SubjectMatter::create([
            'course_id'      => 1,
            'teacher_id'     => 1,
            'classroom_id'   => 1,
            'name'           => 'Subject Matter 1',
            'information'    => 'Ini adalah informasi untuk Materi Pelajaran disini untuk menjelaskan materi yang telah di lampirkan',
            'youtube'        => 'https://www.youtube.com/watch?v=hzLbGHFcmGw',
            'link'           => 'https://drive.google.com/file/d/0B0Qfx8dX9TCvMDZhZjdmYWMtOGVlZi00NmQ3LTg1YWMtM2FkMDk2OThjYjIy/view?usp=sharing',
            'start'          => Carbon::now(), // Start subject
            'end'            => null, // give null if subject no need expired
            'status'         => 1,
        ]);

        /* Table assignments */
        \App\Models\Assignment::create([
            'subject_matter_id'      => 1,
            'name'                   => 'Assignment 1',
            'information'            => 'Ini adalah informasi untuk Tugas disini untuk menjelaskan materi yang telah di lampirkan',
            'youtube'                => 'https://www.youtube.com/watch?v=hzLbGHFcmGw',
            'link'                   => 'https://drive.google.com/file/d/0B0Qfx8dX9TCvMDZhZjdmYWMtOGVlZi00NmQ3LTg1YWMtM2FkMDk2OThjYjIy/view?usp=sharing',
            'start'                  => Carbon::now(), // Start subject
            'end'                    => Carbon::now(), // give null if subject no need expired
            'status'                 => 1,
        ]);

        /* Table assignments_result*/
        \App\Models\Assignment::create([
            'user_id'               => 3,
            'subject_matter_id'     => 1,
            'name'                  => 'Nanti ini dihapus aja',
            'information'           => 'Informasi tugas dari siswa',
            'youtube'               => 'link youtube jika menggunakan video',
            'link'                  => 'link download google drive',
            'grade'                 => null, // Nilai yang di berikan guru
        ]);
    }
}
