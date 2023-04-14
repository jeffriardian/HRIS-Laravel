<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

use Spatie\Permission\Models\Role;
use App\Models\User;
use DB;

class RolePermissionController extends Controller
{
    //METHOD UNTUK MENGAMBIL PERMISSION YANG DIMILIKI OLEH ROLE TERTENTU
    public function getRolePermission(Request $request)
    {   
        //MELAKUKAN QUERY UNTUK MENGAMBIL PERMISSION NAME BERDASARKAN ROLE_ID
        $hasPermission = DB::table('role_has_permissions')
            ->select('permissions.name')
            ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
            ->where('role_id', $request->role_id)->get();
        return response()->json(['status' => 'success', 'data' => $hasPermission], Response::HTTP_OK);
    }

    //FUNGSI INI UNTUK MENGATUR PERMISSION DARI ROLE YANG DIPILIH
    public function setRolePermission(Request $request)
    {
        //VALIDASI
        $this->validate($request, [
            'role_id' => 'required|exists:roles,id'
        ]);

        $role = Role::find($request->role_id); //AMBIL ROLE BERDASARKAN ID
        $role->syncPermissions($request->permissions); //SET PERMISSION UNTUK ROLE TERSEBUT
        //syncPermissions BEKERJA CARA MENGHAPUS SEMUA ROLE YANG DIMILIKI, KEMUDIAN
        //MENYIMPAN DATA YANG BARU
        //PARAMETER PERMISSIONS ADALAH ARRAY YANG BERISI NAME PERMISSIONS
        return response()->json(['status' => 'success'], Response::HTTP_OK);
    }

    //UNTUK MENGATUR ROLE SETIAP USER
    public function setRoleUser(Request $request)
    {
        //VALIDASI
        $this->validate($request, [
            'user_id' => 'required|exists:users,id',
            'role' => 'required'
        ]);

        $user = User::find($request->user_id); //AMBIL USER BERDASARKAN ID
        $user->syncRoles([$request->role]); //SET ROLE UNTUK USER TERKAIT
        return response()->json(['status' => 'success'], Response::HTTP_OK);
    }
}
