<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $article = Article::latest()->get();
        $article = Article::latest()->paginate(2);
        return response()->json([
            "success" => true,
            "message" => "Article berhasil ditampilkan",
            "data" => $article
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'title' => 'required',
            'body' => 'required'
        ]);

        // validasi image
        // $this->validate($request, [
        //     'image' => 'required | image | mimes:jpg,jpeg,png,svg | max:20000000'
        // ]);

        // $foto = $request->file('image');
        // $foto_name = $foto->getClientOriginalName();
        // $store_foto = time().$foto_name;
        // $foto->move(('image'), $store_foto);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        $user = auth()->user();

        $article = $user->articles()->create([
            'title' => $request->title,
            'body' => $request->body,
            // 'image' => $request->image
        ]);

        return response()->json([
            "success" => true,
            "message" => "Article berhasil ditambahkan",
            "data" => $article
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $article = Article::find($id);
        return response()->json([
            "success" => true,
            "message" => "Article berhasil ditampilkan",
            "data" => $article
        ]);
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
        $validator = Validator::make(request()->all(), [
            'title' => 'required',
            'body' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        $article = Article::find($id);
        $article->title = $request->title;
        $article->body = $request->body;
        $article->save();

        return response()->json([
            "success" => true,
            "message" => "Article berhasil diubah",
            "data" => $article
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $article = Article::find($id);
        $article->delete();

        return response()->json([
            "success" => true,
            "message" => "Article berhasil dihapus",
            "data" => $article
        ]);
    }

    public function showArticle()
    {
        $result = [];
        $data = Article::get();

        return $data;
    }

    public function storeArticle(Request $request)
    {
        // dd(123);
        if (Auth::user()) {
            $this->validate($request, [
                'image' => 'required | image | mimes:jpg,jpeg,png | max:20000000'
            ]);

            $image = $request->file('image');
            $image_name = $image->getClientOriginalName();
            $store_image = time() . $image_name;
            $image->move('image', $store_image);

            // $user = auth()->user();

            $article = Article::create([
                'title' => $request->title,
                'body' => $request->body,
                'image' => $store_image
            ]);

            // return response()->json(
            //     Response::HTTP_OK
            // );
            return response()->json([
                "success" => true,
                "message" => "Article berhasil ditambahkan",
                "data" => $article
            ]);
        } else {
            return response()->json([
                Response::HTTP_INTERNAL_SERVER_ERROR
            ]);
        }
    }
}
