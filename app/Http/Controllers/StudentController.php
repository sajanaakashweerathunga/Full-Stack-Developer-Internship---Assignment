<?php

namespace App\Http\Controllers;

use App\Models\Student;
use DB;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Log;

class StudentController extends Controller
{
    public function index()
    {
        return Inertia::render('Student/Index');
    }
    public function getdata()
    {
        $data = Student::all();
        return response()->json($data);
    }

    public function create(Request $request)
    {
        // $data = Student::create($request->all());
        return Inertia::render('Student/CreateUpdate');
    }
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $student = new Student();
            $student->name = $request->name;
            $student->age = $request->age;
            $student->status = $request->status;

            // Handle image upload if provided
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->extension();
                $image->move(public_path('images'), $imageName);
                $student->image = $imageName;
            }

            Log::info($student);
            $student->save();
            DB::commit();

            return redirect(route('student.index'));
        } catch (Exception $ex) {
            Log::info($ex);
        }

    }
    public function edit($id){
        $student = Student::findOrFail($id);
        // dd($student);
        return Inertia::render('Student/CreateUpdate', ['student' => $student]);
    }
    public function update(Request $request){
        try {
            DB::beginTransaction();
            $student = Student::findOrFail($request->id);
            $student->name = $request->name;
            $student->age = $request->age;
            $student->status = $request->status;

            // Handle image upload if provided
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->extension();
                $image->move(public_path('images'), $imageName);
                $student->image = $imageName;
            }

            Log::info($student);
            $student->save();
            DB::commit();

            return redirect(route('student.index'));
        } catch (Exception $ex) {
            Log::info($ex);
        }
    }
    public function delete($id){
        try {
            $student = Student::findOrFail($id);
            $student->delete();
            return response()->json(['message' => 'Student deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete student'], 500);
        }
    }
    public function toggleStatus(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        $newStatus = $request->input('status');

        // Update the student's status
        $student->status = $newStatus;
        $student->save();

        return response()->json(['message' => 'Status toggled successfully'], 200);
    }
}
