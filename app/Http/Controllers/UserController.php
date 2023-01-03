<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Intervention\Image\ImageManagerStatic as Image;

class UserController extends Controller
{
    /**
     * Constructor.
     *
     *
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }


    // Index Users (Web)
    public function user(Request $request)
    {
        $search = trim($request->search);
        $per_page = $request->per_page;
        $users = User::with('role')->orderByCreatedAtDesc()->paginate(10);
        foreach ($users as $user) {
            $avatar = ($user->avatar == null) ? null : base64_encode($user->avatar);
            $user['avatar'] = $avatar;
        }

        $pageConfigs = ['pageHeader' => false, 'footerType' => 'sticky'];

        return view('/content/nebolus/users/user', [
            'pageConfigs' => $pageConfigs
        ])->with('users', $users);
    }

    // Partial Table Users (Web)
    public function partialuser(Request $request)
    {
        $search = trim($request->search);
        $per_page = $request->per_page;
        $users = User::with('role')->orderByCreatedAtDesc()->byName($search)->paginate($per_page);
        foreach ($users as $user) {
            $avatar = ($user->avatar == null) ? null : base64_encode($user->avatar);
            $user['avatar'] = $avatar;
        }

        $pageConfigs = ['pageHeader' => false, 'footerType' => 'sticky'];

        return view('/content/nebolus/users/table-users', [
            'pageConfigs' => $pageConfigs
        ])->with('users', $users);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pageConfigs = ['pageHeader' => false,];
        return view('users.index', compact('pageConfigs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageConfigs = ['pageHeader' => false,];
        return view('users.create', compact('pageConfigs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            if ($extension == 'jpeg' || $extension == 'png' || $extension == 'jpg') {
                //resizes
                $image = Image::make($image)->resize(1280, 960, function ($constraint) {
                    $constraint->aspectRatio();
                });
                Response::make($image->encode('jpeg'));
            }
        } else {
            $image = null;
        }

        $data = array(
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => bcrypt('Mundo.2022'),
            'avatar'    => $image,
            'role_id'   => $request->role
        );
        DB::beginTransaction();
        try {
            $user = User::create($data);
            DB::commit();
            return redirect()->route('users.index')->with('message', 'Item store successfully');
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return back()->with('error', 'Item not store');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $user = User::with('role')->findOrFail($id);
            $avatar = ($user->avatar == null) ? null : base64_encode($user->avatar);
            $user['avatar'] = $avatar;
            return response()->json(['user' => $user], 200);
        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return response()->json(['message' => 'Usuario no encontrado.'], 404);
            }
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
        //dd( $new );
        $pageConfigs = ['pageHeader' => false,];
        $user = User::findOrFail($id);
        return view('users.edit', compact('user', 'pageConfigs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, $id)
    {
        $user = User::findOrFail($id);
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            if ($extension == 'jpeg' || $extension == 'png' || $extension == 'jpg') {
                //resizes
                $image = Image::make($image)->resize(1280, 960, function ($constraint) {
                    $constraint->aspectRatio();
                });
                Response::make($image->encode('jpeg'));
            }
        } else if ( $request->removeImage != 'true' ) {
            $image = $user->avatar;
        } else {
            $image = null;
        }

        DB::beginTransaction();
        try {
            $user->name      = $request->name;
            $user->avatar    = $image;
            $user->role_id   = $request->role;
            $user->save();
            DB::commit();
            return redirect()->route('users.edit', [$user->id])->with('message', 'Item store successfully');
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return back()->with('error', 'Item not store');
        }
    }

    public function updateProfile(UserUpdateRequest $request, $id)
    {
        $user = User::findOrFail($id);
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            if ($extension == 'jpeg' || $extension == 'png' || $extension == 'jpg') {
                //resizes
                $image = Image::make($image)->resize(1280, 960, function ($constraint) {
                    $constraint->aspectRatio();
                });
                Response::make($image->encode('jpeg'));
            }
        } else if ( $request->removeImage != 'true' ) {
            $image = $user->avatar;
        } else {
            $image = null;
        }

        DB::beginTransaction();
        try {
            $user->name      = $request->name;
            $user->avatar    = $image;
            $user->role_id   = $request->role;
            $user->save();
            DB::commit();
            return redirect()->route('users.profile')->with('message', 'Item store successfully');
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return back()->with('error', 'Item not store');
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
        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);
            $user->delete();
            DB::commit();
            return redirect()->route('users.index')->with(["message" => "Item delete success"], 200);
        } catch (Exception $e) {
            DB::rollback();
            if ($e instanceof ModelNotFoundException) {
                return redirect()->route('users.index')->with(["message" => "Item not found"], 404);
            }
            return redirect()->route('users.index')->with(["message" => "Error en Base de Datos: " . $e->getMessage()], 500);
        }
    }

    public function getJson()
    {
        $users = User::all();

        $usersDataTable = array();

        foreach( $users as $key => $user ) {
            $data = array(
                'id'            => $user->id,
                'user'          => $user->name == null ? '' : $user->name,
                'email'         => $user->email == null ? '' : $user->email,
                'newsletter'    => $user->newsletter == null ? 0 : 1,
                'role'          => $user->role == null ? '' : $user->role->name,
                'createdAt'     => Carbon::parse($user->created_at)->format('d-m-y'),
            );

            array_push( $usersDataTable, $data );
        }

        $response = array(
            "draw" => intval(10),
            "iTotalRecords" => count($usersDataTable),
            "iTotalDisplayRecords" => count($usersDataTable),
            "aaData" => $usersDataTable
        );
        echo json_encode($response);
    }

    public function profile()
    {
        //dd( $new );
        $pageConfigs = ['pageHeader' => false,];
        $user = User::findOrFail(auth()->user()->id);
        return view('users.profile', compact('user', 'pageConfigs'));
    }

    public function changePassword()
    {
        $pageConfigs = ['pageHeader' => false,];
        $user = User::findOrFail(auth()->user()->id);
        return view('users.change-password', compact('user', 'pageConfigs'));
    }

    public function updatePassword(ChangePasswordRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail(auth()->user()->id);
            $user->password = bcrypt( trim($request->new_password) );
            $user->save();
            DB::commit();
            return redirect()->route('users.profile')->with(["message" => "password change"], 200);
        } catch (Exception $e) {
            DB::rollback();
            if ($e instanceof ModelNotFoundException) {
                return redirect()->route('users.index')->with(["message" => "Item not found"], 404);
            }
            return redirect()->route('users.profile')->with(["message" => "Error en Base de Datos: " . $e->getMessage()], 500);
        }
    }
}
