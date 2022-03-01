<?php

namespace Anik\Testbench\Tests\Extensions;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

trait Helper
{
    protected function runMigration(): self
    {
        $this->artisan('migrate:fresh', [
            '--path' => realpath(dirname(__DIR__) . '/Migrations'),
            '--realpath' => true,
        ]);

        return $this;
    }

    protected function getEloquentModel(array $fillable = []): Model
    {
        return new class ($fillable) extends Model implements Authenticatable {
            use \Illuminate\Auth\Authenticatable;

            protected $table = 'users';

            public function __construct(array $fillable = [])
            {
                $this->fillable = $fillable;
                parent::__construct();
            }
        };
    }

    protected function createUser(array $attributes = [], array $fillable = ['name', 'email']): Model
    {
        $user = ($this->getEloquentModel($fillable))->fill(array_merge([
            'name' => 'testbench-lumen',
            'email' => 'testbench.lumen@example.com',
        ], $attributes));

        $user->password = app('hash')->make($attributes['password'] ?? '12345');
        $user->save();

        return $user;
    }
}
