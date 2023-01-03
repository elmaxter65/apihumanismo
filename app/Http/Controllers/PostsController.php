<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use App\Models\Status;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PostRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Models\EntryLanguage;
use App\Models\EntryTag;
use App\Models\Language;

class PostsController extends Controller
{
    public function index()
    {
        $pageConfigs = ['pageHeader' => false,];
        return view('posts.index', compact('pageConfigs'));
    }

    public function getJson()
    {
        $posts = Entry::all();

        $postsDataTable = array();
        $postsLastDataTable = array();

        foreach( $posts as $key => $post ) {
            $postsDataTable = $this->loadArray( $post, $postsDataTable );
        }

        $response = array(
            "draw" => intval(10),
            "iTotalRecords" => count($postsDataTable),
            "iTotalDisplayRecords" => count($postsDataTable),
            "aaData" => $postsDataTable
        );
        echo json_encode($response);
    }

    private function loadArray($post, Array $posts)
    {
        $data = array(
            ''              => '',
            'id'            => $post->id,
            'title'         => count($post->entrylanguage) >= 1 ? $post->entrylanguage[0]->title : '',
            'themes'        => $post->theme->name,
            'content_type'  => $post->entrytype->name,
            'createdAt'     => Carbon::parse( $post->created_at )->format('d/m/Y'),
            'status'        => $post->status->name,
            ''              => '',
        );

        array_push( $posts, $data );
        return $posts;
    }

    public function getSlug(Request $request)
    {
        $name   = $request->name;
        $slug   = Str::slug($name);
        $slug   = substr( $slug, 0, 190 );
        echo json_encode($slug);
    }

    public function changeStatus($id = null, $status = null)
    {
        try {
            $post = Entry::findOrFail($id);
            $this->authorize('pass', $post);
            $status = Status::where('name', $status)->first();
            $post->status_id = $status->id;
            $post->save();
            DB::commit();
            return redirect()->route("posts.index")->with('message', 'Item store successfully');
        } catch (\Exception $e) {
            //dd($e);
            DB::rollBack();
            return back()->with('error', 'Item not store');
        }
    }

    public function edit($id)
    {
        $pageConfigs = ['pageHeader' => false,];
        $post = Entry::findOrFail( $id );
        //return var_dump($post);
        return view('posts.edit', compact( 'post', 'pageConfigs'));
    }

    public function create()
    {
        $pageConfigs = ['pageHeader' => false,];
        return view('posts.create', compact('pageConfigs'));
    }

    public function destroy($id)
    {
        $post = Entry::findOrFail($id);
        $this->authorize('pass', $post);
        $post->delete($id);

        return redirect()->route('posts.index');
    }

    public function store(PostRequest $request)
    {
        DB::beginTransaction();

        if ( is_array( $request->themes ) && count( $request->themes ) >= 1 ) {
            $request->themes = $request->themes[0];
        }

        try {
            $data = array(
                'appears_home'          => 0,
                'index_content'         => $request->indexed,
                'likes_number'          => 0,
                'views_number'          => 0,
                'reading_time'          => 0,
                'status_id'             => $request->status,
                'author_id'             => auth()->user()->id,
                'theme_id'              => $request->themes,
                'entry_type_id'         => $request->post_type,
            );

            $post = Entry::create( $data );

            //dd($request->visibility);

            $language = Language::where( 'code', 'ESP' )->first();

            $dataLanguage = array(
                'entry_id'              => $post->id,
                'language_id'           => $language->id,
                'title'                 => $request->title,
                'subtitle'              => $request->subtitle,
                'video_transcription'   => $request->video_transcription,
                'content'               => $request->content,
                'meta_description'      => $request->meta_description,
                'seo_title'             => $request->seo_title,
                'slug'                  => $request->slug,
                'url_video_youtube'     => $request->url_video_youtube,
                'url_video_vimeo'       => $request->url_video_vimeo,
                'url_audio'             => $request->url_audio,
                'h1'                    => $request->header1,
                'h2'                    => $request->header2,
                'h3'                    => $request->header3,
                'h4'                    => $request->header4,
            );

            if ( is_array( $request->tags ) && count( $request->tags ) >= 1 ) {
                $tags = $request->tags;
                foreach ( $tags as $key => $tag ) {
                    $dataTag = array(
                        'entry_id'      => $post->id,
                        'tag_id'        => $tag,
                        'language_id'   => 1,
                    );

                    $entryTag = EntryTag::create( $dataTag );
                }
            }

            $postLanguage = EntryLanguage::create( $dataLanguage );

            DB::commit();
            //dd($data);
            return redirect()->route('posts.index')->with('message', 'Item store successfully');
        } catch (\Exception $e) {
            //dd($e);
            DB::rollBack();
            return back()->with('error', 'Item not store. Server Error');
        }
    }

    public function update(PostUpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $post = Entry::findOrFail( $id );

            if ( is_array( $request->themes ) && count( $request->themes ) >= 1 ) {
                $request->themes = $request->themes[0];
            }

            $post->appears_home          = 0;
            $post->index_content         = $request->indexed;
            $post->likes_number          = 0;
            $post->views_number          = 0;
            $post->reading_time          = 0;
            $post->status_id             = $request->status;
            $post->author_id             = auth()->user()->id;
            $post->theme_id              = $request->themes;
            $post->entry_type_id         = $request->post_type;
            $post->save();

            $language = Language::where( 'code', 'ESP' )->first();

            $postLanguage = EntryLanguage::where( 'entry_id', $id )->where( 'language_id', $language->id )->first();

            $postLanguage->entry_id              = $post->id;
            $postLanguage->language_id           = $language->id;
            $postLanguage->title                 = $request->title;
            $postLanguage->subtitle              = $request->subtitle;
            $postLanguage->video_transcription   = $request->video_transcription;
            $postLanguage->content               = $request->content;
            $postLanguage->meta_description      = $request->meta_description;
            $postLanguage->seo_title             = $request->seo_title;
            $postLanguage->slug                  = $request->slug;
            $postLanguage->url_video_youtube     = $request->url_video_youtube;
            $postLanguage->url_video_vimeo       = $request->url_video_vimeo;
            $postLanguage->url_audio             = $request->url_audio;
            $postLanguage->h1                    = $request->header1;
            $postLanguage->h2                    = $request->header2;
            $postLanguage->h3                    = $request->header3;
            $postLanguage->h4                    = $request->header4;

            $oldTags = EntryTag::where( 'entry_id', $post->id )->get();

            foreach ( $oldTags as $key => $oldTag ) {
                $oldTag = EntryTag::findOrFail( $oldTag->id );
                $oldTag->delete();
            }

            if ( is_array( $request->tags ) && count( $request->tags ) >= 1 ) {
                $tags = $request->tags;
                foreach ( $tags as $key => $tag ) {
                    $dataTag = array(
                        'entry_id'      => $post->id,
                        'tag_id'        => $tag,
                        'language_id'   => 1,
                    );

                    $entryTag = EntryTag::create( $dataTag );
                }
            }

            $postLanguage->save();

            DB::commit();
            return redirect()->route('posts.edit', [$post->id])->with('message', 'Item store successfully');
        } catch (\Exception $e) {
            //dd($e);
            DB::rollBack();
            return back()->with('error', 'Item not store. Server Error');
        }
    }
}
