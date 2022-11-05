# Laravel-JWT
Laravel Auth dengan JWT

Instalasi

1. composer require -w tymon/jwt-auth --ignore-platform-reqs

2. php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"

3. php artisan jwt:secret


Konfigurasi

1. Models > User.php (tambahkan)
	class User extends Authenticatable implements JWTSubject

	public function getJWTIdentifier()
    	{
        	return $this->getKey();
    	}

	public function getJWTCustomClaims()
    	{
        	return [];
    	}

2. config > auth.php
		'defaults' => [
        	'guard' => 'api',
        	'passwords' => 'users',
    	],

	'guards' => [
        'api' => [
            		'driver' => 'jwt',
            		'provider' => 'users',
        	],
    	],

3. routes > api.php

	Route::group(['middleware' => 'api','prefix' => 'auth'], function ($router) {
			Route::post('register', [AuthController::class, 'register']);
			Route::post('login', [AuthController::class, 'login']);
    			Route::post('logout', [AuthController::class, 'logout']);
    			Route::post('refresh', [AuthController::class, 'refresh']);
    			Route::post('me', [AuthController::class, 'me']);

		});

4. php artisan make:controller Auth\AuthController

5. copas isi dari AuthController

6. tambahkan routes dan function register
	public function register()
    	{
        	$validator = Validator::make(request()->all(), [
            		'name' => 'required',
            		'email' => 'required|email|unique:users',
            		'password' => 'required'
        	]);

        	if($validator->fails()){
            		return response()->json($validator->messages());
        	}

        	$user = User::create([
            		'name' => request('name'),
            		'email' => request('email'),
            		'password' => Hash::make(request('password'))
        	]);

        	if($user){
            		return response()->json(['message' => 'Pendaftaran Berhasil']);
        	}else{
            		return response()->json(['message' => 'Pendaftaran Gagal']);
        	}
    	}

7. pada constructor tambahkan register
	$this->middleware('auth:api', ['except' => ['login', 'register']]);
	
	public function register()
    	{
        	$validator = Validator::make(request()->all(), [
            		'name' => 'required',
            		'email' => 'required|email|unique:users',
            		'password' => 'required'
        	]);

        	if($validator->fails()){
            		return response()->json($validator->messages());
        	}

        	$user = User::create([
            		'name' => request('name'),
            		'email' => request('email'),
            		'password' => Hash::make(request('password'))
        	]);

        	if($user){
            		return response()->json(['message' => 'Pendaftaran Berhasil']);
        	}else{
            		return response()->json(['message' => 'Pendaftaran Gagal']);
        	}
    	}

8. jalankan php artisan route:list, untuk mendapatkan daftar endpointnya

