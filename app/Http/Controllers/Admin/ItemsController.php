<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Item;
use App\Category;
use Illuminate\Http\Request;
use Session;

use App\Helpers\ImageHelper;

class ItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $items = Item::where('title', 'LIKE', "%$keyword%")
				->orWhere('content', 'LIKE', "%$keyword%")
				->orWhere('category_id', 'LIKE', "%$keyword%")
				->paginate($perPage);
        } else {
            $items = Item::paginate($perPage);
        }

        return view('admin.items.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $categories = Category::pluck('title','id');
        return view('admin.items.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'title' => 'required',
            'content' => 'required',
            'category_id' => 'required',
            'image' => 'required|mimes:jpeg,jpg,png|image|max:1000'
        ]);
        $requestData = $request->all();
        $item = Item::create($requestData);

        if($request->hasFile('image')){
            $stored_img = ImageHelper::store_image($request->file('image'));
            $item->img_id = $stored_img['img_id'];
            $item->img_url = $stored_img['img_url'];
            $item->save();
        }

        Session::flash('flash_message', 'Item added!');

        return redirect('admin/items');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $item = Item::findOrFail($id);

        return view('admin.items.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $item = Item::findOrFail($id);
        $categories = Category::pluck('title','id');
        return view('admin.items.edit', compact('item','categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
        $this->validate($request, [
			'title' => 'required',
			'content' => 'required',
			'category_id' => 'required'
		]);
        $requestData = $request->all();
        $item = Item::findOrFail($id);
        $item->update($requestData);

        if($request->hasFile('image')){
            $stored_img = ImageHelper::update_image($request->file('image'),$item->img_id);
            $item->img_id = $stored_img['img_id'];
            $item->img_url = $stored_img['img_url'];
            $item->save();
        }

        Session::flash('flash_message', 'Item updated!');

        return redirect('admin/items');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        ImageHelper::delete_image($item->img_id);
        $item->delete();

        Session::flash('flash_message', 'Item deleted!');

        return redirect('admin/items');
    }
}
