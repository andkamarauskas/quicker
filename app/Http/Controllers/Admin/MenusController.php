<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Menu;
use App\Complex;
use Illuminate\Http\Request;
use Session;

class MenusController extends Controller
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
            $menus = Menu::where('title', 'LIKE', "%$keyword%")
				->orWhere('content', 'LIKE', "%$keyword%")
				->orWhere('price', 'LIKE', "%$keyword%")
				->orWhere('complex_id', 'LIKE', "%$keyword%")
				->paginate($perPage);
        } else {
            $menus = Menu::paginate($perPage);
        }

        return view('admin.menus.index', compact('menus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $complexes = Complex::pluck('title','id');
        return view('admin.menus.create',compact('complexes'));
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
			'price' => 'required',
			'complex_id' => 'required'
		]);
        $requestData = $request->all();
        
        Menu::create($requestData);

        Session::flash('flash_message', 'Menu added!');

        return redirect('admin/menus');
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
        $menu = Menu::findOrFail($id);

        return view('admin.menus.show', compact('menu'));
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
        $menu = Menu::findOrFail($id);
        $complexes = Complex::pluck('title','id');

        return view('admin.menus.edit', compact('menu','complexes'));
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
			'price' => 'required',
			'complex_id' => 'required'
		]);
        $requestData = $request->all();
        
        $menu = Menu::findOrFail($id);
        $menu->update($requestData);

        Session::flash('flash_message', 'Menu updated!');

        return redirect('admin/menus');
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
        Menu::destroy($id);

        Session::flash('flash_message', 'Menu deleted!');

        return redirect('admin/menus');
    }
}
