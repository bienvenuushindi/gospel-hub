<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\EmailSubscription;
use App\Models\Post;
use App\Models\PostTag;
//use App\Models\PostType;
use App\Models\Tag;
use App\Models\Type;
use App\Models\User;
//use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
     private $popularAudios=null;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
        $this->popularAudios = PostController::getPopularAudios()->limit(3)->get();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home', ['data' => $this->getHomeData()]);
    }

    public function getHomeData()
    {
//        dd(PostController::getAudios()->limit(10)->get());
        $posts = [
            'audio' => PostController::getPopularAudios()->limit(12)->get(),
            'article' => PostController::getArticles()->limit(6)->get(),

        ];

        return [
            'posts' => $posts,
            'tags' => self::getTagsByGroup(),
            'events' => EventController::getLatestEvents()->get()->reverse(),
            'authors' =>$this->getAuthors()->limit(5)->get(),
        ];
    }

    public function posts_by_month()
    {
        $years = \DB::table('posts')->selectRaw("DATE_FORMAT(created_at, '%Y') AS year, COUNT(*) AS total") ->groupBy('year') ->get();
        return $years;
    }

    public static function getTagsByGroup()
    {
        $tags = collect(PostTag::all('tag_id')->groupBy('tag_id'))->sort()->reverse();
        $data = array();
        $index = 0;
        foreach ($tags as $tag) {
            $id = $tag[$index]['tag_id'];
            $name = Tag::query()->select('name')->find($id)['name'];
            $data[] = [
                'id' => $id,
                'name' => $name,
            ];
        }
        return $data;
    }

    public function downloadAudio($id)
    {
        $filePath = Post::query()->find($id);
        $filePath->increment('download');
        if (is_file(public_path() . '/storage/' . $filePath['description'])) {
            return response()->download(public_path() . '/storage/' . $filePath['description']);
        } else {
            return back()->with('error','It seems this file is missing');
        }

    }

    public function viewAudio($id,$slug = '')
    {
        $post=PostController::getClientSidePost($id)['post'];

        if ($slug !== $post->slug) {
            return redirect()->to($post->url);
        }
        return view('audio', ['post' => $post , 'relatedPost' => PostController::getClientSidePost($id)['related']]);
    }

    public function viewArticle($id,$slug = '')
    {
        $post=PostController::getClientSidePost($id)['post'];

        if ($slug !== $post->slug) {
            return redirect()->to($post->url);
        }
        return view('article', ['post' => $post, 'relatedPost' => PostController::getClientSidePost($id)['related']]);
    }

    public function viewPosts($type)
    {
        $name = '';
        $posts='';
        if ($type === "audios") {
            $name = "audio";
            $posts=PostController::getPopularAudios()->paginate(20);
        } elseif ($type === "articles") {
            $name = "article";
            $posts=PostController::getArticles()->paginate(12);
        }

        return view('posts', ['data' => $posts, 'type' => $name,'popularAudios'=>$this->popularAudios]);
    }

    public function viewEvents()
    {
        return view('events', ['data' => EventController::getAllEvents(),'popularAudios'=>$this->popularAudios]);
    }

    public static function getAuthors(){
        $type = Type::query()->select('id')->where('name', '=', 'author')->first();
        if($type){
            return User::query()->where('type_id', '=', $type['id']);
        }
        return User::query();
//        dd($type);

    }

    public function viewAuthors()
    {
       $authors=$this->getAuthors()->paginate(12);

        return view('authors', ['data' => $authors,'popularAudios'=>$this->popularAudios]);
    }

    public function viewAuthor($id)
    {
        $author = User::query()->find($id);
        return view('author', ['data' => $author, 'postTypeAudio' => PostTypeController::typeAudio()]);
    }

    public function emailSubscription(Request $request)
    {
        $this->validate($request,
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            ]);

        $new = EmailSubscription::query()->create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'subscribed' => 1,
        ]);
        if ($new) {
            return back()->with('success', 'your subscription has been made successfully');
        }
    }

    public function createTeacher()
    {
        return view('become_teacher');
    }

    public function store()
    {
        $data = request()->validate([
                "name" => 'required',
                "body" => 'required',
                "reply_via_email" => '',
                "email" => 'required'
            ]
        );
        if (Contact::query()->create($data)) {
            return back()->with('success', 'Thanks for your message. We\'ll be in touch.');
        }
    }
}
