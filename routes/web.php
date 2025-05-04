<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\BuildingPlansController;

Route::view('/', 'dashboard')->name('home');
Volt::route('blogs', 'pages.blogs.index')
->name('blogs.index');
Volt::route('blogs/{slug}', 'pages.blogs.show')
->name('blog.show');
Volt::route('page/{slug}', 'pages.page.show')
->name('page.show');
Volt::route('contact-us', 'pages.page.contact-us')
->name('page.contact-us');
Volt::route('project/{slug}', 'pages.projects.show')
->name('project.show');
Volt::route('projects', 'pages.projects.index')
->name('projects.index');
Volt::route('building-plan/{slug}', 'pages.building-plan.show')
->name('building-plan.show');
Volt::route('services', 'pages.service.index')
->name('services.index');
Volt::route('service/{slug}', 'pages.service.show')
->name('service.show');


Route::get('building_plan/{id}', [BuildingPlansController::class, 'index'])->name('building_plan.index');
Route::get('building_plan/building/{id}', [BuildingPlansController::class, 'buildingStore'])->name('building.store');
// Route::view('dashboard', 'dashboard')
//    // ->middleware(['auth', 'verified'])
//     ->name('home');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
