<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Validator;
use Exception;
use DB;
use App\Models\Entry;
use App\Models\Status;
use App\Models\EntryLanguage;
use App\Models\EntryLike;
use App\Models\EntryType;
use App\Models\EntryChapter;
use App\Models\EntryChapterLanguage;
use App\Models\EntryChapterLike;
use App\Models\EntryChapterUser;
use App\Models\Language;
use App\Models\User;
use App\Models\UserPreference;
use App\Models\Section;
use App\Models\Theme;
use Auth;

class EntryController extends Controller
{
    /**
     * Constructor.
     *
     *
     */
    public function __construct()
    {
       $this->middleware(['auth:api'])->except(['index', 'show', 'showBySlug', 'showMostValuedEntries', 'showMostSeenEntries', 'updateViewsNumber', 'relatedEntries', 'insertChapters', 'insertChapterLanguages']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $title = trim($request->title);
            $theme_id = $request->theme_id;
            $language = trim($request->language);

            if ( isset($request->language) )
            {
                $language = Language::select('id')->where('code', '=', $language)->first();
                if ($language != null) {
                    $language_id = $language->id;
                }
            } else {
                $language = Language::select('id')->where('code', '=', 'ESP')->first();
                $language_id = $language->id;
            }

            if ( !isset($request->order_by) )
            {
                $term = 'recent';
            } else {
                $term = trim($request->order_by);
            }

            if ( !isset($request->user_id) )
            {
                $user_id = 0;
                $my_likes = [];
            } else {
                $user_id = $request->user_id;
                $my_likes = EntryLike::where('user_id', '=', $request->user_id)->pluck('entry_id')->toArray();
            }

            if ( isset($request->type) )
            {
                if ($request->type == 1) {
                    $entry_type = EntryType::select('id')->where('code', '=', 'VID')->first();
                    if ($entry_type != null) {
                        $entry_type_id = $entry_type->id;
                    }
                }

                if ($request->type == 2) {
                    $entry_type = EntryType::select('id')->where('code', '=', 'OTR')->first();
                    if ($entry_type != null) {
                        $entry_type_id = $entry_type->id;
                    }
                }

                if ($request->type == -1) {
                    $entry_type_id = -1;
                }
            } else {
                $entry_type_id = -1;
            }

            if ( ( isset($request->per_page) && isset($request->page) ) || ( isset($request->per_page) && !isset($request->page) ) )
            {
                $per_page = $request->per_page;

                if ( !isset($request->page) ) $request->page = 1;
            } else {
                $count = Entry::count();
                $per_page = $count;
                $request->page = 1;
            }

            if ( isset($request->reading_time_min) && isset($request->reading_time_max) )
            {
                $reading_time = [];
                for ($i=$request->reading_time_min; $i <= $request->reading_time_max; $i++) {
                    $reading_time[] = $i;
                }
            } else {
                $reading_time = [];
            }

            if ( isset($request->themes) )
            {
                $ithemes = $request->themes;
                $arrayithemes = explode(",", $ithemes);
                $its = [];
                for ($ithms=0; $ithms < count($arrayithemes); $ithms++) {
                    $its[] = $arrayithemes[$ithms];
                }

                $themes = Theme::whereIn('slug', $its)->pluck('id')->toArray();

            } else {
                $themes = [];
            }

            $status = Status::select('id')->where('code','=','PUB')->first();

            $entries = Entry::select(
                'id',
                'index_content',
                'likes_number',
                DB::raw('0 as like_me'),
                'views_number',
                'reading_time',
                'appears_home',
                'author_id',
                'status_id',
                'theme_id',
                'entry_type_id',
                'created_at',
                'updated_at'
            )
            ->with('theme')
            ->with('entrytype')
            ->with('entrylanguage.language')
            ->with('entrytag.tag')
            ->with('entrychapter.entrychapterlanguage.language')
            ->with('entrychapter.entrychapterlikes.user')
            ->byTitle($title)
            ->byReadingTime($reading_time)
            ->byFormat($entry_type_id)
            ->byThemes($themes)
            ->byLanguage($language_id)
            ->where('status_id', '=', $status->id)
            ->orderByTerm($term)
            ->paginate($per_page);

            if($user_id > 0){
                foreach($entries as $entry){
                    $currentEntry = $entry->id;

                    foreach($my_likes as $value){
                        $currentLike = $value;
                        $mylike = 0;

                        if ($currentEntry === $currentLike) {
                            $mylike = 1;
                            break;
                        }
                    }
                    $entry->like_me = $mylike;
                }
            }

            return response()->json(['entries' => $entries], 200);
        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return response()->json(['message' => 'Entradas no encontradas.'], 404);
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        try {

            $like = 0;
            if ( isset($request->user_id) )
            {
                $user_id = $request->user_id;
                $entry_like = EntryLike::where('user_id', '=', $request->user_id)->where('entry_id', '=', $id)->first();
                if ($entry_like != null) {
                    $like = 1;
                }
            }

            $status = Status::select('id')->where('code','=','PUB')->first();

            $entry = Entry::select(
                'id',
                'index_content',
                'likes_number',
                DB::raw('0 as like_me'),
                'views_number',
                'reading_time',
                'appears_home',
                'author_id',
                'status_id',
                'theme_id',
                'entry_type_id',
                'created_at',
                'updated_at'
            )
            ->with('author')
            ->with('status')
            ->with('theme')
            ->with('entrytype')
            ->with('entrylanguage.language')
            ->with('entrytag.tag')
            ->with('entrychapter.entrychapterlanguage.language')
            ->with('entrychapter.entrychapterlikes.user')
            ->where('status_id', '=', $status->id)
            ->findOrFail($id);

            $entry->like_me = $like;

            return response()->json(['entry' => $entry], 200);
        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return response()->json(['message' => 'Entrada no encontrada.'], 404);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showBySlug(Request $request, $slug)
    {
        try {

            $entrylanguage = EntryLanguage::where('slug', '=', $slug)->first();
            if ($entrylanguage == null) {
                return response()->json(["status_code" => 404, "status" => "error", 'message' => 'No se ha podido encontrar el slug consultado. Inténtelo nuevamente o contacte al administrador del sistema.'], 404);
            }
            $entry_id = $entrylanguage->entry_id;

            $like = 0;
            if ( isset($request->user_id) )
            {
                $user_id = $request->user_id;
                $entry_like = EntryLike::where('user_id', '=', $request->user_id)->where('entry_id', '=', $entry_id)->first();
                if ($entry_like != null) {
                    $like = 1;
                }
            }

            $status = Status::select('id')->where('code','=','PUB')->first();

            $entry = Entry::select(
                'id',
                'index_content',
                'likes_number',
                DB::raw('0 as like_me'),
                'views_number',
                'reading_time',
                'appears_home',
                'author_id',
                'status_id',
                'theme_id',
                'entry_type_id',
                'created_at',
                'updated_at'
            )
            ->with('author')
            ->with('status')
            ->with('theme')
            ->with('entrytype')
            ->with('entrylanguage.language')
            ->with('entrytag.tag')
            ->with('entrychapter.entrychapterlanguage.language')
            ->with('entrychapter.entrychapterlikes.user')
            ->where('status_id', '=', $status->id)
            ->where('id', '=', $entry_id)
            ->first();

            $entry->like_me = $like;

            return response()->json(['entry' => $entry], 200);
        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return response()->json(['message' => 'Entrada no encontrada.'], 404);
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function saveFavoriteEntry(Request $request, $entry_id, $user_id)
    {
        DB::beginTransaction();
        try {

            $msg = '';

            $entry = Entry::where('id', '=', $entry_id)->first();

            if ($entry == null) {
                return response()->json(["message" => "Entrada no encontrada"], 404);
            }

            $user = User::where('id', '=', $user_id)->first();

            if ($user == null) {
                return response()->json(["message" => "Usuario no encontrado"], 404);
            }

            $like = EntryLike::where('user_id', '=', $user_id)->where('entry_id', '=', $entry_id)->first();

            if ($like != null) {
                $dellike = $like->forceDelete();

                if ($dellike) {
                    $likes_number = $entry->likes_number;
                    $entry->likes_number = $likes_number - 1;
                    $entry->save();
                }

                $msg = 'La entrada ha dejado de ser favorita.';
            } else {
                $addlike = EntryLike::create([
                    'user_id' => $user_id,
                    'entry_id' => $entry_id
                ]);

                if ($addlike) {
                    $likes_number = $entry->likes_number;
                    $entry->likes_number = $likes_number + 1;
                    $entry->save();
                }

                $msg = 'La entrada se ha marcado como favorita.';
            }

            DB::commit();
            return response()->json(["message" => $msg], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(["message" => "Error en Base de Datos: ".$e->getMessage()], 500);
        }
    }

    /**
     * Like a Capítulos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function saveFavoriteEntryChapter(Request $request, $entry_chapter_id, $user_id)
    {
        DB::beginTransaction();
        try {

            $msg = '';

            $entry_chapter = EntryChapter::where('id', '=', $entry_chapter_id)->first();

            if ($entry_chapter == null) {
                return response()->json(["message" => "Capítulo no encontrado"], 404);
            }

            $user = User::where('id', '=', $user_id)->first();

            if ($user == null) {
                return response()->json(["message" => "Usuario no encontrado"], 404);
            }

            $like = EntryChapterLike::where('user_id', '=', $user_id)->where('entry_chapter_id', '=', $entry_chapter_id)->first();

            if ($like != null) {
                $dellike = $like->forceDelete();

                if ($dellike) {
                    $likes_number = $entry_chapter->likes_number;
                    $entry_chapter->likes_number = $likes_number - 1;
                    $entry_chapter->save();
                }

                $msg = 'El capítulo ha dejado de ser favorito.';
            } else {
                $addlike = EntryChapterLike::create([
                    'user_id' => $user_id,
                    'entry_chapter_id' => $entry_chapter_id
                ]);

                if ($addlike) {
                    $likes_number = $entry_chapter->likes_number;
                    $entry_chapter->likes_number = $likes_number + 1;
                    $entry_chapter->save();
                }

                $msg = 'El capítulo se ha marcado como favorito.';
            }

            DB::commit();
            return response()->json(["message" => $msg], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(["message" => "Error en Base de Datos: ".$e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showFavoriteEntriesByUser(Request $request, $user_id)
    {
        try {
            $title = trim($request->title);
            $theme_id = $request->theme_id;
            $language = trim($request->language);

            $user = User::where('id', '=', $user_id)->first();
            if ($user == null) {
                return response()->json(['message' => 'No se ha podido encontrar el usuario consultado. Inténtelo nuevamente o contacte al administrador del sistema.'], 404);
            }

            $entry_likes = EntryLike::where('user_id', '=', $user->id)
            ->pluck('entry_id')
            ->toArray();

            if ( isset($request->language) )
            {
                $language = Language::select('id')->where('code', '=', $language)->first();
                if ($language != null) {
                    $language_id = $language->id;
                }
            } else {
                $language = Language::select('id')->where('code', '=', 'ESP')->first();
                $language_id = $language->id;
            }

            if ( !isset($request->order_by) )
            {
                $term = 'recent';
            } else {
                $term = trim($request->order_by);
            }

            if ( isset($request->type) )
            {
                if ($request->type == 1) {
                    $entry_type = EntryType::select('id')->where('code', '=', 'VID')->first();
                    if ($entry_type != null) {
                        $entry_type_id = $entry_type->id;
                    }
                }

                if ($request->type == 2) {
                    $entry_type = EntryType::select('id')->where('code', '=', 'OTR')->first();
                    if ($entry_type != null) {
                        $entry_type_id = $entry_type->id;
                    }
                }

                if ($request->type == -1) {
                    $entry_type_id = -1;
                }
            } else {
                $entry_type_id = -1;
            }

            if ( ( isset($request->per_page) && isset($request->page) ) || ( isset($request->per_page) && !isset($request->page) ) )
            {
                $per_page = $request->per_page;

                if ( !isset($request->page) ) $request->page = 1;
            } else {
                $count = Entry::count();
                $per_page = $count;
                $request->page = 1;
            }

            if ( isset($request->reading_time_min) && isset($request->reading_time_max) )
            {
                $reading_time = [];
                for ($i=$request->reading_time_min; $i <= $request->reading_time_max; $i++) {
                    $reading_time[] = $i;
                }
            } else {
                $reading_time = [];
            }

            if ( isset($request->themes) )
            {
                $ithemes = $request->themes;
                $arrayithemes = explode(",", $ithemes);
                $its = [];
                for ($ithms=0; $ithms < count($arrayithemes); $ithms++) {
                    $its[] = $arrayithemes[$ithms];
                }

                $themes = Theme::whereIn('slug', $its)->pluck('id')->toArray();

            } else {
                $themes = [];
            }

            $status = Status::select('id')->where('code','=','PUB')->first();

            $entries = Entry::select(
                'id',
                'index_content',
                'likes_number',
                DB::raw('0 as like_me'),
                'views_number',
                'reading_time',
                'appears_home',
                'author_id',
                'status_id',
                'theme_id',
                'entry_type_id',
                'created_at',
                'updated_at'
            )
            ->with('theme')
            ->with('entrytype')
            ->with('entrylanguage.language')
            ->with('entrytag.tag')
            ->with('entrychapter.entrychapterlanguage.language')
            ->with('entrychapter.entrychapterlikes.user')
            ->whereIn('id', $entry_likes)
            ->byTitle($title)
            ->byReadingTime($reading_time)
            ->byFormat($entry_type_id)
            ->byThemes($themes)
            ->byLanguage($language_id)
            ->where('status_id', '=', $status->id)
            ->orderByTerm($term)
            ->paginate($per_page);

            if($user_id > 0){
                foreach($entries as $entry){
                    $currentEntry = $entry->id;

                    foreach($entry_likes as $value){
                        $currentLike = $value;
                        $mylike = 0;

                        if ($currentEntry === $currentLike) {
                            $mylike = 1;
                            break;
                        }
                    }
                    $entry->like_me = $mylike;
                }
            }

            return response()->json(['entries' => $entries], 200);
        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return response()->json(['message' => 'Entradas del usuario no encontradas.'], 404);
            }
        }
    }

    /**
     * Lo más valorado (Más likes).
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showMostValuedEntries(Request $request)
    {
        try {

            $language = trim($request->language);
			$title = trim($request->title);

            if ( isset($request->language) )
            {
                $language = Language::select('id')->where('code', '=', $language)->first();
                if ($language != null) {
                    $language_id = $language->id;
                }
            } else {
                $language = Language::select('id')->where('code', '=', 'ESP')->first();
                $language_id = $language->id;
            }

            if ( isset($request->type) )
            {
                if ($request->type == 1) {
                    $entry_type = EntryType::select('id')->where('code', '=', 'VID')->first();
                    if ($entry_type != null) {
                        $entry_type_id = $entry_type->id;
                    }
                }

                if ($request->type == 2) {
                    $entry_type = EntryType::select('id')->where('code', '=', 'OTR')->first();
                    if ($entry_type != null) {
                        $entry_type_id = $entry_type->id;
                    }
                }

                if ($request->type == -1) {
                    $entry_type_id = -1;
                }
            } else {
                $entry_type_id = -1;
            }

            if ( isset($request->reading_time_min) && isset($request->reading_time_max) )
            {
                $reading_time = [];
                for ($i=$request->reading_time_min; $i <= $request->reading_time_max; $i++) {
                    $reading_time[] = $i;
                }
            } else {
                $reading_time = [];
            }

            if ( isset($request->themes) )
            {
                $ithemes = $request->themes;
                $arrayithemes = explode(",", $ithemes);
                $its = [];
                for ($ithms=0; $ithms < count($arrayithemes); $ithms++) {
                    $its[] = $arrayithemes[$ithms];
                }

                $themes = Theme::whereIn('slug', $its)->pluck('id')->toArray();

            } else {
                $themes = [];
            }

            if ( ( isset($request->per_page) && isset($request->page) ) || ( isset($request->per_page) && !isset($request->page) ) )
            {
                $per_page = $request->per_page;

                if ( !isset($request->page) ) $request->page = 1;
            } else {
                $count = Entry::count();
                $per_page = $count;
                $request->page = 1;
            }

            if ( !isset($request->user_id) )
            {
                $user_id = 0;
                $my_likes = [];
            } else {
                $user_id = $request->user_id;
                $my_likes = EntryLike::where('user_id', '=', $request->user_id)->pluck('entry_id')->toArray();
            }

            $status = Status::select('id')->where('code','=','PUB')->first();

            $entries = Entry::select(
                'id',
                'index_content',
                'likes_number',
                DB::raw('0 as like_me'),
                'views_number',
                'reading_time',
                'appears_home',
                'author_id',
                'status_id',
                'theme_id',
                'entry_type_id',
                'created_at',
                'updated_at'
            )
            ->with('theme')
            ->with('entrytype')
            ->with('entrylanguage.language')
            ->with('entrytag.tag')
            ->with('entrychapter.entrychapterlanguage.language')
            ->with('entrychapter.entrychapterlikes.user')
			->byTitle($title)
			->byReadingTime($reading_time)
			->byFormat($entry_type_id)
			->byThemes($themes)
            ->byLanguage($language_id)
            ->where('status_id', '=', $status->id)
            ->orderBy('likes_number','DESC')
            ->paginate($per_page);

            if($user_id > 0){
                foreach($entries as $entry){
                    $currentEntry = $entry->id;

                    foreach($my_likes as $value){
                        $currentLike = $value;
                        $mylike = 0;

                        if ($currentEntry === $currentLike) {
                            $mylike = 1;
                            break;
                        }
                    }
                    $entry->like_me = $mylike;
                }
            }

            return response()->json(['entries' => $entries], 200);
        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return response()->json(['message' => 'Entradas no encontradas.'], 404);
            }
        }
    }

    /**
     * Pensados para tí (Preferencias de los usuarios).
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showThoughtForYouEntries(Request $request, $user_id)
    {
        try {

            $ids_themes = UserPreference::where('user_id', '=', $user_id)->pluck('preference_id')->toArray();

            $language = trim($request->language);
			$title = trim($request->title);

            if ( isset($request->language) )
            {
                $language = Language::select('id')->where('code', '=', $language)->first();
                if ($language != null) {
                    $language_id = $language->id;
                }
            } else {
                $language = Language::select('id')->where('code', '=', 'ESP')->first();
                $language_id = $language->id;
            }

            if ( isset($request->type) )
            {
                if ($request->type == 1) {
                    $entry_type = EntryType::select('id')->where('code', '=', 'VID')->first();
                    if ($entry_type != null) {
                        $entry_type_id = $entry_type->id;
                    }
                }

                if ($request->type == 2) {
                    $entry_type = EntryType::select('id')->where('code', '=', 'OTR')->first();
                    if ($entry_type != null) {
                        $entry_type_id = $entry_type->id;
                    }
                }

                if ($request->type == -1) {
                    $entry_type_id = -1;
                }
            } else {
                $entry_type_id = -1;
            }

            if ( isset($request->reading_time_min) && isset($request->reading_time_max) )
            {
                $reading_time = [];
                for ($i=$request->reading_time_min; $i <= $request->reading_time_max; $i++) {
                    $reading_time[] = $i;
                }
            } else {
                $reading_time = [];
            }

            if ( ( isset($request->per_page) && isset($request->page) ) || ( isset($request->per_page) && !isset($request->page) ) )
            {
                $per_page = $request->per_page;

                if ( !isset($request->page) ) $request->page = 1;
            } else {
                $count = Entry::count();
                $per_page = $count;
                $request->page = 1;
            }

            $entry_likes = EntryLike::where('user_id', '=', $user_id)
            ->pluck('entry_id')
            ->toArray();

            $status = Status::select('id')->where('code','=','PUB')->first();

            $entries = Entry::select(
                'id',
                'index_content',
                'likes_number',
                DB::raw('0 as like_me'),
                'views_number',
                'reading_time',
                'appears_home',
                'author_id',
                'status_id',
                'theme_id',
                'entry_type_id',
                'created_at',
                'updated_at'
            )
            ->with('theme')
            ->with('entrytype')
            ->with('entrylanguage.language')
            ->with('entrytag.tag')
            ->with('entrychapter.entrychapterlanguage.language')
            ->with('entrychapter.entrychapterlikes.user')
            ->byTitle($title)
            ->byReadingTime($reading_time)
            ->byFormat($entry_type_id)
            ->byLanguage($language_id)
            ->where('status_id', '=', $status->id)
            ->whereIn('theme_id', $ids_themes)
            ->paginate($per_page);

            if($user_id > 0){
                foreach($entries as $entry){
                    $currentEntry = $entry->id;

                    foreach($entry_likes as $value){
                        $currentLike = $value;
                        $mylike = 0;

                        if ($currentEntry === $currentLike) {
                            $mylike = 1;
                            break;
                        }
                    }
                    $entry->like_me = $mylike;
                }
            }

            return response()->json(['entries' => $entries], 200);
        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return response()->json(['message' => 'Entradas no encontradas.'], 404);
            }
        }
    }

    /**
     * Popular en Mundo Crypto (lo más visto).
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showMostSeenEntries(Request $request)
    {
        try {

            $language = trim($request->language);
			$title = trim($request->title);

            if ( isset($request->language) )
            {
                $language = Language::select('id')->where('code', '=', $language)->first();
                if ($language != null) {
                    $language_id = $language->id;
                }
            } else {
                $language = Language::select('id')->where('code', '=', 'ESP')->first();
                $language_id = $language->id;
            }

            if ( isset($request->type) )
            {
                if ($request->type == 1) {
                    $entry_type = EntryType::select('id')->where('code', '=', 'VID')->first();
                    if ($entry_type != null) {
                        $entry_type_id = $entry_type->id;
                    }
                }

                if ($request->type == 2) {
                    $entry_type = EntryType::select('id')->where('code', '=', 'OTR')->first();
                    if ($entry_type != null) {
                        $entry_type_id = $entry_type->id;
                    }
                }

                if ($request->type == -1) {
                    $entry_type_id = -1;
                }
            } else {
                $entry_type_id = -1;
            }

            if ( isset($request->reading_time_min) && isset($request->reading_time_max) )
            {
                $reading_time = [];
                for ($i=$request->reading_time_min; $i <= $request->reading_time_max; $i++) {
                    $reading_time[] = $i;
                }
            } else {
                $reading_time = [];
            }

            if ( isset($request->themes) )
            {
                $ithemes = $request->themes;
                $arrayithemes = explode(",", $ithemes);
                $its = [];
                for ($ithms=0; $ithms < count($arrayithemes); $ithms++) {
                    $its[] = $arrayithemes[$ithms];
                }

                $themes = Theme::whereIn('slug', $its)->pluck('id')->toArray();

            } else {
                $themes = [];
            }

            if ( ( isset($request->per_page) && isset($request->page) ) || ( isset($request->per_page) && !isset($request->page) ) )
            {
                $per_page = $request->per_page;

                if ( !isset($request->page) ) $request->page = 1;
            } else {
                $count = Entry::count();
                $per_page = $count;
                $request->page = 1;
            }

            if ( !isset($request->user_id) )
            {
                $user_id = 0;
                $my_likes = [];
            } else {
                $user_id = $request->user_id;
                $my_likes = EntryLike::where('user_id', '=', $request->user_id)->pluck('entry_id')->toArray();
            }

            $status = Status::select('id')->where('code','=','PUB')->first();

            $entries = Entry::select(
                'id',
                'index_content',
                'likes_number',
                DB::raw('0 as like_me'),
                'views_number',
                'reading_time',
                'appears_home',
                'author_id',
                'status_id',
                'theme_id',
                'entry_type_id',
                'created_at',
                'updated_at'
            )
            ->with('theme')
            ->with('entrytype')
            ->with('entrylanguage.language')
            ->with('entrytag.tag')
            ->with('entrychapter.entrychapterlanguage.language')
            ->with('entrychapter.entrychapterlikes.user')
            ->byTitle($title)
            ->byReadingTime($reading_time)
            ->byFormat($entry_type_id)
            ->byThemes($themes)
            ->byLanguage($language_id)
            ->where('status_id', '=', $status->id)
            ->orderBy('views_number','DESC')
            ->paginate($per_page);

            if($user_id > 0){
                foreach($entries as $entry){
                    $currentEntry = $entry->id;

                    foreach($my_likes as $value){
                        $currentLike = $value;
                        $mylike = 0;

                        if ($currentEntry === $currentLike) {
                            $mylike = 1;
                            break;
                        }
                    }
                    $entry->like_me = $mylike;
                }
            }

            return response()->json(['entries' => $entries], 200);
        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return response()->json(['message' => 'Entradas no encontradas.'], 404);
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateViewsNumber(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $entry = Entry::findOrFail($id);

            if ($entry == null) {
                return response()->json(["message" => "Entrada no encontrada"], 404);
            }

            $views_number = $entry->views_number;
            $entry->views_number = $views_number + 1;
            $entry->save();

            DB::commit();
            return response()->json(["message" => "El número de vistas de la entrada se ha actualizado exitosamente."], 200);
        } catch (Exception $e) { /* No es creado (500) - Rollback */
            DB::rollback();
            return response()->json(["message" => "Error en Base de Datos: ".$e->getMessage()], 500);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function relatedEntries(Request $request, $slug_related_entry)
    {
        try {
            $entryLanguage = EntryLanguage::where('slug', '=', $slug_related_entry)->first();

            if ($entryLanguage == null) {
                return response()->json(["message" => "Entrada no encontrada"], 404);
            }
            $entry_id = $entryLanguage->entry_id;

            $entry = Entry::where('id', '=', $entry_id)->first();
            if ($entry == null) {
                return response()->json(["message" => "Entrada no encontrada"], 404);
            }
            $theme_id = $entry->theme_id;

            $language = trim($request->language);

            if ( isset($request->language) )
            {
                $language = Language::select('id')->where('code', '=', $language)->first();
                if ($language != null) {
                    $language_id = $language->id;
                }
            } else {
                $language = Language::select('id')->where('code', '=', 'ESP')->first();
                $language_id = $language->id;
            }

            if ( ( isset($request->per_page) && isset($request->page) ) || ( isset($request->per_page) && !isset($request->page) ) )
            {
                $per_page = $request->per_page;

                if ( !isset($request->page) ) $request->page = 1;
            } else {
                $count = Entry::count();
                $per_page = $count;
                $request->page = 1;
            }

            if ( !isset($request->user_id) )
            {
                $user_id = 0;
                $my_likes = [];
            } else {
                $user_id = $request->user_id;
                $my_likes = EntryLike::where('user_id', '=', $request->user_id)->pluck('entry_id')->toArray();
            }

            $status = Status::select('id')->where('code','=','PUB')->first();

            $entries = Entry::select(
                'id',
                'index_content',
                'likes_number',
                DB::raw('0 as like_me'),
                'views_number',
                'reading_time',
                'appears_home',
                'author_id',
                'status_id',
                'theme_id',
                'entry_type_id',
                'created_at',
                'updated_at'
            )
            ->with('theme')
            ->with('entrytype')
            ->with('entrylanguage.language')
            ->with('entrytag.tag')
            ->with('entrychapter.entrychapterlanguage.language')
            ->with('entrychapter.entrychapterlikes.user')
            ->whereNotIn('id', [$entry_id])
            ->byLanguage($language_id)
            ->where('status_id', '=', $status->id)
            ->where('theme_id', '=', $theme_id)
            ->inRandomOrder()
            ->paginate($per_page);

            if($user_id > 0){
                foreach($entries as $entry){
                    $currentEntry = $entry->id;

                    foreach($my_likes as $value){
                        $currentLike = $value;
                        $mylike = 0;

                        if ($currentEntry === $currentLike) {
                            $mylike = 1;
                            break;
                        }
                    }
                    $entry->like_me = $mylike;
                }
            }

            return response()->json(['entries' => $entries], 200);
        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return response()->json(['message' => 'Entradas no encontradas.'], 404);
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateChapterByUser(Request $request, $entry_chapter_id, $user_id)
    {
        $validator = Validator::make($request->all(), [
            'finalized'  => 'nullable|integer'
        ]);

        //Si la validación no pasa (se dispara el error 422)
        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors = json_decode($errors);
            return response()->json(["status_code" => 422, "message" => "Error en envío de datos", "errors" => $errors], 422);
        } else {
            DB::beginTransaction();
            try {
                $entrychapter = EntryChapter::where('id', '=', $entry_chapter_id)->first();

                if ($entrychapter == null) {
                    return response()->json(["message" => "Capítulo no encontrado"], 404);
                }

                $user = User::where('id', '=', $user_id)->first();

                if ($user == null) {
                    return response()->json(["message" => "Usuario no encontrado"], 404);
                }

                $entrychapteruser = EntryChapterUser::where('entry_chapter_id', '=', $entry_chapter_id)->where('user_id', '=', $user_id)->first();

                if ($entrychapteruser == null) {

                    EntryChapterUser::create([
                        'entry_chapter_id' => $entry_chapter_id,
                        'user_id' => $user_id,
                        'finalized' => 0
                    ]);

                } else {
                    $entrychapteruser->finalized = $request->finalized;
                    $entrychapteruser->save();
                }

                DB::commit();
                return response()->json(["message" => "El capítulo actual que visualiza el usuario se ha actualizado exitosamente."], 200);
            } catch (Exception $e) { /* No es creado (500) - Rollback */
                DB::rollback();
                return response()->json(["message" => "Error en Base de Datos: ".$e->getMessage()], 500);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function chaptersByUser($user_id)
    {
        try {
            $entrychapteruser = EntryChapterUser::with('entrychapter.entrychapterlanguage')->with('entrychapter.entry')->get();
            return response()->json(['entrychapteruser' => $entrychapteruser], 200);
        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return response()->json(["status_code" => 404, 'message' => 'Capítulos no encontrados o usuario no existe.'], 404);
            }
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function insertChapters(Request $request)
    {
        try {

            $entries = Entry::get();
            $language = Language::select('id')->where('code', '=', 'ESP')->first();
            $language_id = $language->id;

            foreach($entries as $entry){
                $order = 1;

                for ($i=0; $i < 5; $i++) { 
                    $n = $i + 1;

                    $entrychapter = EntryChapter::create([
                        'order' => $order,
                        'entry_id' => $entry->id
                    ]);

                    /*$entrychapter_id = $entrychapter->id;

                    if ($entrychapter) {
                        EntryChapterLanguage::create([
                            'language_id' => $language_id,
                            'entry_chapter_id' => $entrychapter_id,
                            'title' => 'Capítulo '.$n,
                            'content' => '¡ÚLTIMA HORA BITCOIN! | SEMANA DE INDECISIÓN | Precio, análisis técnico y gráficos de BTC',
                            'slug' => 'capitulo-'.$n,
                            'url_video' => 'https://www.youtube.com/watch?v=9Z8AOi_hBtc'
                        ]);
                    }*/

                    $order++;

                }

            }

            return response()->json(['msg' => 'Capítulos cargados!'], 200);
        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return response()->json(['message' => 'Entradas no encontradas.'], 404);
            }
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function insertChapterLanguages(Request $request)
    {
        try {

            $entrychapters = EntryChapter::get();
            $language = Language::select('id')->where('code', '=', 'ESP')->first();
            $language_id = $language->id;

            $n = 1;

            foreach($entrychapters as $entrychapter){

                EntryChapterLanguage::create([
                    'language_id' => $language_id,
                    'entry_chapter_id' => $entrychapter->id,
                    'title' => 'Capítulo '.$n,
                    'content' => '¡ÚLTIMA HORA BITCOIN! | SEMANA DE INDECISIÓN | Precio, análisis técnico y gráficos de BTC',
                    'slug' => 'capitulo-'.$n,
                    'url_video' => 'https://www.youtube.com/watch?v=9Z8AOi_hBtc'
                ]);

                $n++;

            }

            return response()->json(['msg' => 'Idiomas de Capítulos cargados!'], 200);
        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return response()->json(['message' => 'Entradas no encontradas.'], 404);
            }
        }
    }

}
