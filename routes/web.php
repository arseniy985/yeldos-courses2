<?php

use App\Http\Controllers\API\TestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\CoursesController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CourseTestController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Courses;
use App\Models\User;
use App\Models\Test;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/register', function () {
    return view('auth.register');
})->name('register');
Route::get('/login', function () {
    return view('auth.login');
})->name('login');


Route::middleware('auth')->group(function () {
    Route::get('/', function () { return Inertia::render('Welcome', [ 'courses' => Courses::OrderBy('id', 'DESC')->get() ]); })->name('home');

    Route::get('/manage-students', function () { return Inertia::render('ManageStudents', [ 'students' => User::where('role', 'student')->OrderBy('id', 'DESC')->get() ]); })->name('manageStudents');
    Route::get('/view-student/{id}', [StudentController::class, 'show'])->name('student.show');

    Route::get('/view-course/{id}', [CoursesController::class, 'show'])->name('course.show');
    Route::get('/watch/{id}', [CoursesController::class, 'watchepisode'])->name('course.watchepisode');
    Route::get('/manage-course', function () { return Inertia::render('ManageCourse', [ 'courses' => Courses::OrderBy('id', 'DESC')->get() ]); })->name('manageCourses');

    Route::get('/add-course', function () {return Inertia::render('AddCourse'); })->name('addCourse');
    Route::post('/addcourse', [CoursesController::class, 'store'])->name('course.store');
    Route::delete('/videos/{id}', [CoursesController::class, 'destroy'])->name('course.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Маршруты для работы с тестами курсов
    Route::get('/course/{courseId}/tests', function($courseId) {
        return Inertia::render('Tests/Index', [
            'course' => Courses::findOrFail($courseId),
            'tests' => Test::where('courses_id', '=' ,$courseId)->orderBy('id', 'DESC')->get()
        ]);
    })->name('course.tests');

    Route::get('/course/{courseId}/tests/create', function($courseId) {
        return Inertia::render('Tests/Create', [
            'course' => Courses::findOrFail($courseId)
        ]);
    })->name('course.tests.create');

    Route::get('/tests/{id}/edit', function($id) {
        return Inertia::render('Tests/Edit', [
            'test' => Test::with('questions.answers')->findOrFail($id)
        ]);
    })->name('tests.edit');

    // Маршруты для сохранения и обновления тестов
    Route::post('/tests', [CourseTestController::class, 'store'])->name('tests.store');
    Route::put('/tests/{id}', [CourseTestController::class, 'update'])->name('tests.update');

    // Маршрут для удаления теста
    Route::delete('/tests/{id}', [CourseTestController::class, 'destroy'])->name('tests.destroy');

    // Маршруты для студентов для прохождения тестов
    Route::get('/student/tests', function() {
        return Inertia::render('Tests/StudentTests', [
            'tests' => Test::with(['course', 'questions'])->get()
        ]);
    })->name('student.tests');

    Route::get('/student/tests/{id}', function($id) {
        return Inertia::render('Tests/Take', [
            'test' => Test::with(['questions.answers', 'course', 'results' => function($query) {
                $query->where('user_id', Auth::id());
            }])->findOrFail($id)
        ]);
    })->name('student.test.take');

    Route::get('/student/test-results', function() {
        return Inertia::render('Tests/Results', [
            'results' => Auth::user()->testResults()->with('test.course')->orderBy('created_at', 'DESC')->get()
        ]);
    })->name('student.test-results');

    // Маршрут для просмотра детального результата конкретного теста
    Route::get('/student/test-result/{id}', function($id) {
        $result = App\Models\TestResult::with(['test.questions.answers'])->findOrFail($id);

        // Проверяем, принадлежит ли результат текущему пользователю
        if ($result->user_id !== Auth::id()) {
            abort(403, 'Доступ запрещен');
        }

        return Inertia::render('Tests/TestResult', [
            'result' => $result
        ]);
    })->name('student.test.result');

    // Маршрут для отправки ответов теста
    Route::post('/student/submit-test', [App\Http\Controllers\API\TestController::class, 'submitAnswers'])->name('student.test.submit');
});

Route::get('auth/{provider}/redirect', [ProviderController::class, 'redirect']);
Route::get('auth/{provider}/callback', [ProviderController::class, 'callback']);
Route::get('test/result', [TestController::class, 'getResultAnalytics']);

require __DIR__.'/auth.php';
