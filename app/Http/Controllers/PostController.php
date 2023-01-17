<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostTag;
use App\Models\PostType;
use App\Models\Tag;
use App\Models\User;
use Carbon\Carbon;
use File;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;


//use Intervention\Image\Image;


/**
 * Class PostController
 * @package App\Http\Controllers
 */
class PostController extends Controller
{
    private $user;

    /**
     * PostController constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->middleware(['author']);
        $this->user = $user;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *
     */
    public function index()
    {
        $this->user=Auth::user();
        return view('author.posts.index',$this->indexProcessor($this->user));
    }

    public static function indexProcessor(User $user){
        $articles = self::userArticles($user);
        $audios = self::userAudios($user);
        $countByYears=self::posts_by_years($user);
        $commentNumbers = 0;

        return  compact('articles', 'audios', 'commentNumbers','countByYears');
    }

    public static function getPopularAudios()
    {
        return self::getAudios()->orderByRaw('created_at-views-download desc');
    }

    public static function userArticles(User $user)
    {

        return $user->articlePosts()->get();
    }

    public static function userAudios(User $user)
    {
        return $user->audioPosts()->get();
    }


    private static function separateUserPosts(User $user)
    {
        return [
            'articles' => $user->articlePosts(),
            'audios' => $user->audioPosts()
        ];
    }

    public static function handlePost($posts)
    {

        $postTypes = PostType::all();
        $data = array();

        foreach ($postTypes as $postType) {
            if (isset($posts[$postType['id']])) {
                $data[$postType['name']] = $posts[$postType['id']];
            }
        }
        return [
            $data
        ];
    }

    /**
     * @param Post $post
     * @return int
     */
    public static function commentNumbers(Post $post)
    {
        return count($post->postComments()->select('id')->get());
    }

    /**
     * @param User $author
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    private static function getUserPosts(User $author)
    {
//        dd($author->audioPosts()->get());
        return $author->posts()->orderByDesc('created_at')->get();
//        return $author->audioPosts();
    }
    public static function posts_by_years(User $user)
    {
        return  [
            'audios'=>(self::countAudiosByYears($user)),
            'articles'=>(self::countArticlesByYears($user))
        ];
    }


    private static function countAudiosByYears(User $user){
        $audioByYears =$user->posts()->selectRaw("DATE_FORMAT(created_at, '%Y') AS year, COUNT(*) AS total")
            ->where('post_type_id', '=', PostTypeController::typeAudio())
            ->groupBy('year')->get()->reverse();
        return $audioByYears;
    }
    private  static function  countArticlesByYears(User $user){
        $articlesByYears =  $user->posts()->selectRaw("DATE_FORMAT(created_at, '%Y') AS year, COUNT(*) AS total")
            ->where('post_type_id', '=', PostTypeController::typeArticle())
            ->groupBy('year')->get()->reverse();
        return $articlesByYears;
    }

    private static function countAudiosByMonths($year){
        $audioByMonths= Auth::user()->posts()->selectRaw("DATE_FORMAT(created_at, '%b') AS month, COUNT(*) AS total")
            ->where('post_type_id', '=', PostTypeController::typeAudio())
            ->whereRaw("DATE_FORMAT(created_at, '%Y') = ?",[$year])
            ->orderBy('month')
            ->groupBy('month')->get()->reverse();
        return $audioByMonths;
    }
    private static function countArticlesByMonths($year){
        $articlesByMonths=Auth::user()->posts()->selectRaw("DATE_FORMAT(created_at, '%b') AS month, COUNT(*) AS total")
            ->where('post_type_id', '=', PostTypeController::typeArticle())
            ->whereRaw("DATE_FORMAT(created_at, '%Y') = ?",[$year])
            ->orderBy('month')
            ->groupBy('month')->get()->reverse();
        return $articlesByMonths;
    }

    public  function fetch($postType,$year,$user=null,$month=null)
    {
        $author=User::query()->find($user);
        return view('author.posts.listall', $this->fetchBy($postType,$year,$author,$month));
    }

    public function fetchBy($postType,$year,User $user=null,$month=null){
        $this->user=$user==null?Auth::user():$user;
        $results= array();
        $posts=array();
        $selected_year=$year;
        $selected_month=$month;
        $countByMonths=array();
        $query=$this->user;

        if($postType == TypeController::$AUDIOTYPE){
            $countByMonths= self::countAudiosByMonths($year);

            $posts=$query->audioPosts()
                ->whereRaw("DATE_FORMAT(created_at, '%Y') = ?",[$year]);
        }
        if($postType == 'article'){

            $countByMonths= self::countArticlesByMonths($year);

            $posts=$query->articlePosts()
                ->whereRaw("DATE_FORMAT(created_at, '%Y') = ?",[$year]);
        }

        else{
            $posts=$query->posts()
                ->whereRaw("DATE_FORMAT(created_at, '%Y') = ?",[$year]);
        }

        if($month)  $results=$posts->whereMonth('created_at',Carbon::parse($month)->month);
        else $results=$posts;

        $list = $results->orderBy('created_at','desc')->paginate();
        $commentNumbers=0;
        $countByYears=$this->posts_by_years($this->user);
        $articles = count(self::userArticles($this->user));
        $audios = count(self::userAudios($this->user));

        return compact('list','commentNumbers','postType','articles','audios','countByYears','countByMonths','selected_year','selected_month');
    }
    /**
     * @param Post $post
     * @return mixed
     */
    public static function getPostCategoryName(Post $post)
    {
        return $post->postType()->select('name')->get()->first();
    }

    /**
     * @param $postType
     * @return array
     */
    public  function listAllBy($postType)
    {
        if($postType==TypeController::$AUDIOTYPE) return  self::userAudios($this->user);

        return self::userAudios($this->user);
    }

    /**
     * @param $postType
     * @param User|null $user
     * @return Factory|View
     */
    public function listAll($postType,User $user=null)
    {
        $this->user=$user==null?Auth::user():$user;
        $list = ($postType == TypeController::$AUDIOTYPE)
            ?$this->user->audioPosts()->orderBy('created_at','desc')->paginate()
            : $this->user->articlePosts()->orderBy('created_at','desc')->paginate();
        $commentNumbers=0;
        $countByYears=$this->posts_by_years($this->user);
        $articles = count(self::userArticles($this->user));
        $audios = count(self::userAudios($this->user));

        return view('author.posts.listall', compact('list','commentNumbers','postType','articles','audios','countByYears'));
    }



    public static function getAudios()
    {
        return self::audios();
    }

    public static function getArticles()
    {

        return self::articles()->orderBy('created_at','desc');
    }

    private static function articles(){
        return Post::query()->where('post_type_id', '=', PostTypeController::typeArticle());
    }
    private static function audios(){
        return Post::query()->where('post_type_id', '=', PostTypeController::typeAudio());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param string $type
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $teachings = $this->getTeachings();
        $type = 'article';
        $tags = $this->getTags();

        return view('author.posts.create', compact('teachings', 'type', 'tags'));
    }

    public function createAudio()
    {
        $teachings = $this->getTeachings();
        $type = 'audio';
        $tags = $this->getTags();

        return view('author.posts.create', compact('teachings', 'type', 'tags'));
    }

    protected function getTeachings()
    {
        return $this->user->teachings()->orderBy('created_at')->get();
    }

    private function getTags()
    {
        return Tag::query()->orderBy('name')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
//        dd($request);
//
        $published = 0;
        $key = 'article';
        $value = 'required|min:20|string';
        $description = $request->input('article');
        $duration = '';

        if (request()->has('audio_file')) {
            $key = 'audio_file';
            $value = 'required|file|mimes:audio/mpeg,mpga,mp3,wav,aac';
//            $value = 'required|file|mimes:mpga,wav,audio/aac,audio/mp3,audio/mpeg';
            $description = 'link';
            $duration = $request->input('duration');
        }

        $this->validate($request, $this->validationRules($key, $value));

        if ($request->input('published') == 1) {
            $published = $request->input('published');
        }

//        dd($request);
        $tags = $this->handleTags($request->input('tags'));

        $post = $this->postCreate($request, $published, $description, $duration);
        //Adding tags to post, Sync() the easy way
        $post->url_key=Str::slug($post->user()->pluck('name')['0']." ".$post->id." ".$post->title);
        $post->save();
        $post->tags()->sync($tags);

//        dd($tags);
        self::storeFile($post, 'post_image_file');
        self::storeFile($post, 'audio_file');

        return back()->with('success', 'This inform you that your post  has been inserted successful');

    }

    private function validationRules($key, $value)
    {
        return [
            'title' => 'required|min:2',
            'short_description_area' => 'required|min:5|string',
            'teaching' => 'sometimes|integer',
            'tags' => 'array|exists:tags,id',
            'published' => 'boolean',
//            'post_type'=>'required|integer',
            'duration' => 'string',
            $key => $value,
            'post_image_file' => 'sometimes|file|image',

        ];
    }

    private function postCreate($request, $published, $description, $duration)
    {

        $post = Post::query()->create($this->createDataList($request, $published, $description, $duration)
        );
        return $post;
    }

    private function createDataList($request, $published, $description, $duration)
    {
//        dd($request);
        return [
            'title' => $request->input('title'),
            'img' => $request->input('post_image_file'),
            'published' => $published,
            'description' => $description,
            'duration' => $duration,
            'short_description' => $request->input('short_description_area'),
            'teaching_id' => $request->input('teaching'),
            'user_id' => Auth::id(),
            'post_type_id' => $request->input('post_type')
        ];
    }

    private function handleTags($request)
    {
        $tags = $request;
        $existingTags = array();
        $newTags = array();
        if (is_array($tags) || is_object($tags))
        {
            foreach ($tags as $tag) {
                if (!is_numeric($tag)) {
                    $tagName = TagController::generateTagName($tag);
                    $tag = Tag::firstOrCreate(['name' => $tagName]);
                    $newTags[] = $tag['id'];
                } else {
                    $existingTags[] = $tag;
                }
            }
        }


        return array_merge($existingTags, $newTags);

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
//    public function show($id)
//    {
//        //
//        $post=$this->getUserPosts()->where('id','=',$id)->first();
////        dd($post);
//        return view('author.posts.show',['post'=>$post]);
//    }


    public function show($id)
    {

        return view('author.posts.show', ['post' => self::getAuthorSidePost($id)['post'], 'relatedPost' => self::getAuthorSidePost($id)['related']]);
    }

    public static function getAuthorSidePost($id)
    {
        $post = Post::query()->find($id);
        if($post){
            $relatedPost = Post::query()->where('post_type_id', '=', $post['post_type_id'])
                ->where('user_id', '=', Auth::id())->limit(10)->get();
            return [
                'post' => $post,
                'related' => $relatedPost
            ];
        }
        return [
            'post' => array(),
            'related' => array()
        ];
    }

    public static function getClientSidePost($id)
    {
        $post = Post::query()->find($id);
        if($post){
            $relatedPost = Post::query()->where('post_type_id', '=', $post['post_type_id'])
                ->limit(10)->get();
            return [
                'post' => $post,
                'related' => $relatedPost
            ];
        }
        return [
            'post' => array(),
            'related' => array()
        ];

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $post = Post::query()->find($id);
        $tags = PostTag::query()->where('post_id', '!=', $id)->join('tags', 'tag_id', '=', 'tags.id')->get();
        $postTags = $post->tags()->get();
//        dd($tags);
        return view('author.posts.edit', ['post' => $post, 'teachings' => $this->getTeachings(), 'tags' => $tags, 'postTags' => $postTags]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        $published = 0;
        $key = 'article';
        $value = 'required|min:20|string';
        $description = $request->input('article');

        if (request()->has('audio_file')) {
            $key = 'audio_file';
            $value = 'sometimes|file|mimes:mpga,wav';
            $description = 'link';
        }
        $this->validate($request, [
            'title' => 'required|min:2',
            'short_description_area' => 'required|min:5|string',
            'teaching' => 'required|integer',
            'published' => 'boolean',

            'post_image_file' => 'sometimes|file|image'
        ]);

        if ($request->input('published') == 1) {
            $published = $request->input('published');
        }

        $tags = $this->handleTags($request->input('tags'));
        $post = Post::query()->find($id);
        $postUpdate = $post->update($this->updateDataList($request, $published, $description));
        if ($postUpdate) {
            //Adding tags to post, Sync() the easy way
            $post->tags()->sync($tags);
            self::storeFile($post, 'post_image_file');
            self::storeFile($post, 'audio_file');

            return redirect()->back()->with('postUpdated', 'This inform you that your update has been successful');
        }

        return back()->withInput();
    }

    private function updateDataList($request, $published, $description)
    {
        $array1 = [
            'title' => $request->input('title'),
            'published' => $published,
            'short_description' => $request->input('short_description_area'),
            'teaching_id' => $request->input('teaching'),
        ];
        if ($request->input('post_image_file') != null) {
            $array1 = array_merge($array1, ['img' => $request->input('post_image_file')]);
        }

        if ($description != null) {
            return array_merge($array1, ['description' => $description]);
        }
        return $array1;

    }

    public function delete($id){
        $post=Post::query()->find($id);
        return view('author.posts.delete', compact('post'));
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $post=Post::query()->select('img','description','post_type_id')->find($id);

        if($post->post_type_id == PostTypeController::typeAudio()){
//            dd(File::exists(public_path('storage/').$post->img));
            if(File::exists(public_path('storage/').$post->img)) File::delete(public_path('storage/').$post->img);
            if(File::exists(public_path('storage/').$post->description)) File::delete(public_path('storage/').$post->description);

        }else{
//            dd(storage_path('app/public/').$post->description);
            if (File::exists(public_path('storage/').$post->img)){
                File::delete(public_path('storage/').$post->img);
            }
        }
        Post::destroy($id);
        return redirect()->back()
            ->with('success', ' successfully deleted,Thank you ');
    }

    private function validateRequest()
    {
        return request()->validate([
            'title' => 'required|min:2',
            'short_description_area' => 'required|min:5|string',
            'teaching' => 'integer',
            'published' => 'boolean',
            'post_type' => 'required|integer',
            'article' => 'required|min:20|string',
            'post_image_file' => 'sometimes|file|image|max:5000',
            'audio_file' => 'sometimes|file|mimes:application/octet-stream,audio/mpeg,mpga,mp3,wav'
        ]);
    }

    public static function storeFile($post, $form_input_name)
    {
        if (request()->has($form_input_name)) {

            $path = 'uploads/articles/images';
            $column = 'img';
            $path_small = 'uploads/articles/images/small';
            $cover = request()->file($form_input_name);
            $extension = $cover->getClientOriginalExtension();
//            Storage::disk('public')->put($cover->getFilename().'.'.$extension,  File::get($cover));
            if ($form_input_name == 'audio_file') {
                $path = 'uploads/audios';
                $column = 'description';
            } elseif ($form_input_name == 'teaching_image_file') {
                $path = 'uploads/theme/images';
                $path_small = 'uploads/theme/images/small';
                $column = 'image';
            } elseif ($form_input_name == 'event_image_file') {
                $path = 'uploads/events/images';
                $path_small = 'uploads/events/images/small';
                $column = 'poster_image';
            }

            $post->update(
                [
                    $column => request()->file($form_input_name, '')->storeAs($path, $post['id'].'-'.$post['title'] . '.' . $extension, 'public')
                ]
            );
//            if($form_input_name != 'audio_file') {
//                 $image = Image::make(public_path('storage/' . $post[$column]))->fit(300,300);
//                 $image->save();
//             }
        }
    }


}
