<?php
namespace App\Providers;

use App\Orchid\Resources;
use Illuminate\Support\ServiceProvider;
use Orchid\Crud\Arbitrator;

class CrudServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Arbitrator $arbitrator)
    {
        $arbitrator->resources([
            Resources\PostResource::class,
            Resources\CategoryResource::class,
        ]);
    }
}