<?php

namespace App\Http\Controllers\Admin\ACL;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;
use Spatie\Permission\Models\Permission;
use DataTables;

class PermissionController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!Auth::user()->hasPermissionTo('Listar Permissões')) {
            abort(403, 'Acesso não autorizado');
        }
        $permissions = Permission::all();

        if ($request->ajax()) {
            $data = $permissions;
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a class="btn btn-xs btn-primary mx-1 shadow" title="Editar" href="permission/' . $row->id . '/edit"><i class="fa fa-lg fa-fw fa-pen"></i></a>' . '<a class="btn btn-xs btn-danger mx-1 shadow" title="Excluir" href="permission/destroy/' . $row->id . '" onclick="return confirm(\'Confirma a exclusão desta permissão?\')"><i class="fa fa-lg fa-fw fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.acl.permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::user()->hasPermissionTo('Criar Permissões')) {
            abort(403, 'Acesso não autorizado');
        }
        return view('admin.acl.permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Auth::user()->hasPermissionTo('Criar Permissões')) {
            abort(403, 'Acesso não autorizado');
        }
        $check = Permission::where('name', $request->name)->get();
        if ($check->count() > 0) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Nome da permissão já está em uso!');
        }
        $data = $request->all();
        $permission = Permission::create($data);
        if ($permission->save()) {
            return redirect()
                ->route('admin.permission.index')
                ->with('success', 'Permissão cadastrada!');
        } else {
            return redirect()
                ->route('admin.permission.index')
                ->withInput()
                ->with('error', 'Falha ao cadastrar a permissão!');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Auth::user()->hasPermissionTo('Editar Permissões')) {
            abort(403, 'Acesso não autorizado');
        }
        $permission = Permission::where('id', $id)->first();
        if (empty($permission->id)) {
            abort(403, 'Acesso não autorizado');
        }
        return view('admin.acl.permissions.edit', compact('permission'));
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
        if (!Auth::user()->hasPermissionTo('Editar Permissões')) {
            abort(403, 'Acesso não autorizado');
        }
        $check = Permission::where('name', $request->name)->where('id', '!=', $id)->get();
        if ($check->count() > 0) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'O nome desta permissão já está em uso!');
        }
        $data = $request->all();
        $permission = Permission::where('id', $id)->first();
        if ($permission->update($data)) {
            return redirect()
                ->route('admin.permission.index')
                ->with('success', 'Atualização realizada!');
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Auth::user()->hasPermissionTo('Excluir Permissões')) {
            abort(403, 'Acesso não autorizado');
        }

        $permission = Permission::where('id', $id)->first();
        if (empty($permission->id)) {
            abort(403, 'Acesso não autorizado');
        }

        if ($permission->delete()) {
            return redirect()
                ->route('admin.permission.index')
                ->with('success', 'Exclusão realizada!');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Erro ao excluir!');
        }
    }
}
