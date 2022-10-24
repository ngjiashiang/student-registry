<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use App\Models\Student;
use App\Exports\StudentsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentsImport;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $students = Student::paginate(request()->all());
        return response()->json(['students'=>$students]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string',
            'course' => 'required|string',
        ]);

        $student = new Student;
        $student->name = $request->name;
        $student->email = $request->email;
        $student->course = $request->course;
        $student->save();

        return response()->json(['msg'=>'Student added', 'student'=>$student], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $students = Student::where('name', $id)->first();
        if(! $students) {
            $students = Student::where('email', $id)->first();
        }
        if(! $students){
            return response()->json(['error'=>'not found'], 400);
        }
        return response()->json(['students'=>$students]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $student = Student::where('id', $id)->first();

        if($request->exists('name')){
            $student->name = $request->name;
        }
        if($request->exists('email')){
            $student->email = $request->email;
        }
        if($request->exists('course')){
            $student->course = $request->course;
        }

        $student->save();

        return response()->json(['msg'=>'student changed', 'student'=>$student], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $student = Student::find($id);
        if($student) {
            $student->delete();
            return response()->json(['msg'=>'student deleted', 'student'=>$student], 200);
        }
        return response()->json(['msg'=>'no such student found'], 400);
    }

    public function export() {
        return Excel::download(new StudentsExport, 'students.xlsx');
    }

    public function import(Request $request) {
        Student::truncate();
        Excel::import(new StudentsImport, request()->file('import'));
        return response()->json(['msg'=>'updated', 'updated students table' => Student::all()], 200);
    }
}
